<?php

namespace LeagueOfData\Repository\Match;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\MatchPlayer;
use LeagueOfData\Entity\Match\MatchPlayerInterface;

/**
 * Match Player object DB Repository
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlMatchPlayerRepository implements StoreRepositoryInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Connection
     */
    private $dbConn;

    /**
     * @var array Player List 
     */
    private $matchPlayers;

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add objects to internal array
     *
     * @param array $players
     */
    public function add(array $players)
    {
        foreach ($players as $player) {
            if ($player instanceof MatchPlayerInterface) {
                $this->matchPlayers[] = $player;
                continue;
            }
            $this->logger->error('Incorrect object supplied to MatchPlayer repository', [$player]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->matchPlayers = [];
    }

    /**
     * Factory for creating objects
     *
     * @param  array $player
     * @return EntityInterface
     */
    public function create(array $player): EntityInterface
    {
        return new MatchPlayer(
            $player['match_id'],
            $player['participant_id'],
            $player['account_id'],
            $player['champion_id'],
            $player['region']
        );
    }

    /**
     * Fetch Match List
     *
     * @param  string $query SQL Query
     * @param  array  $where SQL Where parameters
     * @return array MatchPlayer Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->matchPlayers = [];

        $this->logger->debug('Fetching players from DB');

        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);

        $this->logger->debug(count($this->matchPlayers)." players fetch from DB");

        return $this->matchPlayers;
    }

    /**
     * Store the player objects in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->matchPlayers)." new/updated players");

        foreach ($this->matchPlayers as $player) {
            if ($player->getAccountID() !== 0 && $player->getParticipantID() !== 0) {
                $this->storeFullData($player);
                continue;
            }
            if ($player->getParticipantID() !== 0) {
                $this->storeParticipantData($player);
                continue;
            }
            if ($player->getAccountID() !== 0) {
                $this->storeAccountData($player);
                continue;
            }

            $this->logger->debug("Unable to store player data.", $player);
        }
    }

    /**
     * Store player using full key data
     *
     * @param MatchPlayer $player
     * @return null
     */
    public function storeFullData(MatchPlayer $player)
    {
        $result = $this->dbConn->fetchAll("SELECT * FROM match_players WHERE match_id = :match_id "
            . "AND region = :region AND (account_id = :account_id OR account_id = 0) "
            . "AND (participant_id = :participant_id OR participant_id = 0) AND champion_id = :champion_id",
            $player->getKeyData());

        if ($result) {
            $currentEntry = $this->create($result[0]);
            $this->dbConn->update('match_players', $this->convertPlayerToArray($player), $currentEntry->getKeyData());
            return;
        }

        $this->dbConn->insert('match_players', $this->convertPlayerToArray($player));
    }

    /**
     * Store data when we know the participant ID only
     *
     * @param MatchPlayer $player
     * @return null
     */
    public function storeParticipantData(MatchPlayer $player)
    {
        $result = $this->dbConn->fetchAll("SELECT * FROM match_players WHERE match_id = :match_id "
            . "AND region = :region AND participant_id = :participant_id", $player->getKeyData());

        if ($result) {
            $currentEntry = $this->create($result[0]);
            $this->dbConn->update('match_players', $this->convertPlayerToArray($player), $currentEntry->getKeyData());
            return;
        }

        if ($this->isUniqueChampionInMatch($player)) {
            $result = $this->dbConn->fetchAll("SELECT * FROM match_players WHERE match_id = :match_id "
                . "AND region = :region AND champion_id = :champion_id", $player->getKeyData());

            if ($result) {
                $currentEntry = $this->create($result[0]);
                $this->dbConn->update('match_players', $this->convertPlayerToArray($player),
                    $currentEntry->getKeyData());
                return;
            }

            $this->dbConn->insert('match_players', $this->convertPlayerToArray($player));
        }

        // TODO: Make sure there are enough entries of the champion.
    }

    /**
     * Store data when we know the account ID only
     *
     * @param MatchPlayer $player
     * @return null
     */
    public function storeAccountData(MatchPlayer $player)
    {
        if (!$this->dbConn->fetchAll("SELECT account_id FROM match_players WHERE match_id = :match_id "
            . "AND region = :region AND account_id = :account_id", $player->getKeyData())) {

            $result = $this->dbConn->fetchAll("SELECT * FROM match_players WHERE match_id = :match_id "
                . "AND region = :region AND champion_id = :champion_id", $player->getKeyData());

            if (count($result) === 1) {
                $currentEntry = $this->create($result[0]);
                $this->dbConn->update('match_players', $this->convertPlayerToArray($player),
                    $currentEntry->getKeyData());
                return;
            }

            if (count($result) === 0) {
                $this->dbConn->insert('match_players', $this->convertPlayerToArray($player));
            }
        }
    }

    /**
     * Is this the only occurance of this champion in the match?
     *
     * @param MatchPlayer $player
     * @return bool
     */
    public function isUniqueChampionInMatch(MatchPlayer $player): bool
    {
        foreach ($this->matchPlayers as $otherPlayer) {
            if ($otherPlayer->getChampionID() === $player->getChampionID()
                && $otherPlayer->getParticipantID() !== $player->getParticipantID()) {

                return false;
            }
        }

        return true;
    }

    /**
     * Transfer object out to another repository
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->matchPlayers;
    }

    /**
     * Convert result data into MatchPlayer objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $player) {
                $this->matchPlayers[] = $this->create($player);
            }
        }
    }

    /**
     * Converts MatchPlayer object into SQL data array
     *
     * @param  MatchPlayerInterface $player
     * @return array
     */
    private function convertPlayerToArray(MatchPlayerInterface $player): array
    {
        return [
            'match_id' => $player->getMatchID(),
            'account_id' => $player->getAccountID(),
            'participant_id' => $player->getParticipantID(),
            'champion_id' => $player->getChampionID(),
            'region' => $player->getRegion()
        ];
    }
}

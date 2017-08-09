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
                $this->matchPlayers[$player->getMatchID()][] = $player;
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
     * Store the summoner objects in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->matchPlayers)." new/updated players");

        $accountSelect = "SELECT account_id FROM match_players WHERE match_id = :match_id AND account_id = :account_id "
            . "AND region = :region";
        $participantSelect = "SELECT participant_id FROM match_players WHERE match_id = :match_id "
            . "AND participant_id = :participant_id AND region = :region";
        
        foreach ($this->matchPlayers as $match) {
            foreach ($match as $player) {
                $keyData = $player->getKeyData();
                if (0 !== $player->getAccountID() && $this->dbConn->fetchAll($accountSelect, $player->getKeyData())) {
                    unset($keyData['participant_id']);
                    $this->dbConn->update('match_players', $this->convertPlayerToArray($player), $keyData);
                    continue;
                }
                if (0 !== $player->getParticipantID()
                    && $this->dbConn->fetchAll($participantSelect, $player->getKeyData())) {

                    unset($keyData['account_id']);
                    $this->dbConn->update('match_players', $this->convertPlayerToArray($player), $keyData);
                    continue;
                }
                $this->dbConn->insert('match_players', $this->convertPlayerToArray($player));
            }
        }
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
                $this->matchPlayers[$player['match_id']][] = $this->create($player);
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

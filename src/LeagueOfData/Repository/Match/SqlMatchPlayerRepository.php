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
    private $players;

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
                $this->players[$player->getAccountID()] = $player;
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
        $this->players = [];
    }

    /**
     * Factory for creating objects
     *
     * @param array $player
     * @return EntityInterface
     */
    public function create(array $player): EntityInterface
    {
        return new MatchPlayer(
            $player['match_id'],
            $player['account_id'],
            $player['champion_id'],
            $player['region']
        );
    }

    /**
     * Fetch Match List
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array MatchPlayer Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->players = [];

        $this->logger->debug('Fetching players from DB');

        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);

        $this->logger->debug(count($this->players)." players fetch from DB");

        return $this->players;
    }

    /**
     * Store the summoner objects in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->players)." new/updated players");

        $select = "SELECT account_id FROM match_players WHERE match_id = :match_id AND account_id = :account_id"
            . " AND region = :region";

        foreach ($this->players as $player) {
            if ($this->dbConn->fetchAll($select, $player->getKeyData())) {
                $this->dbConn->update('match_players', $this->convertPlayerToArray($player), $player->getKeyData());
                continue;
            }
            $this->dbConn->insert('match_players', $this->convertPlayerToArray($player));
        }
    }

    /**
     * Transfer object out to another repository
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->players;
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
                $this->players[$player['account_id']] = $this->create($player);
            }
        }
    }

    /**
     * Converts MatchPlayer object into SQL data array
     *
     * @param MatchPlayerInterface $player
     * @return array
     */
    private function convertPlayerToArray(MatchPlayerInterface $player): array
    {
        return [
            'match_id' => $player->getMatchID(),
            'account_id' => $player->getAccountID(),
            'champion_id' => $player->getChampionID(),
            'region' => $player->getRegion()
        ];
    }
}

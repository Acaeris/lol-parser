<?php

namespace LeagueOfData\Repository\Match;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\Match;
use LeagueOfData\Entity\Match\MatchInterface;

/**
 * Match object DB Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlMatchRepository implements StoreRepositoryInterface
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
     * @var array Match List
     */
    private $matches;

    public function __construct(
        Connection $dbConn,
        LoggerInterface $logger
    ) {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add objects to internal array
     *
     * @param array $matches
     */
    public function add(array $matches)
    {
        foreach ($matches as $match) {
            if ($match instanceof MatchInterface) {
                $this->matches[$match->getMatchID()] = $match;
                continue;
            }
            $this->logger->error('Incorrect object supplied to Match repository', [$match]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->matches = [];
    }

    /**
     * Factory for creating objects
     *
     * @param array $match
     * @return EntityInterface
     */
    public function create(array $match): EntityInterface
    {
        return new Match(
            $match['match_id'],
            $match['match_mode'],
            $match['match_type'],
            $match['duration'],
            $match['version'],
            $match['region']
        );
    }

    /**
     * Fetch Match
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array Match Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->matches = [];

        $this->logger->debug('Fetching match from DB');

        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);

        $this->logger->debug(count($this->matches)." matches fetched from DB");

        return $this->matches;
    }

    /**
     * Store the summoner objects in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->matches)." new/updated matches");

        $select = "SELECT match_id FROM matches WHERE match_id = :match_id AND region = :region";

        foreach ($this->matches as $match) {
            if ($this->dbConn->fetchAll($select, $match->getKeyData())) {
                $this->dbConn->update('matches', $this->convertMatchToArray($match), $match->getKeyData());
                continue;
            }
            $this->dbConn->insert('matches', $this->convertMatchToArray($match));
        }
    }

    /**
     * Transfer object out to another repository
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->matches;
    }

    /**
     * Convert result data into Match objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $match) {
                $this->matches[$match['match_id']] = $this->create($match);
            }
        }
    }

    /**
     * Converts Match object into SQL data array
     *
     * @param MatchInterface $match
     * @return array
     */
    private function convertMatchToArray(MatchInterface $match): array
    {
        return [
            'match_id' => $match->getMatchID(),
            'match_mode' => $match->getMode(),
            'match_type' => $match->getType(),
            'duration' => $match->getDuration(),
            'version' => $match->getVersion(),
            'region' => $match->getRegion()
        ];
    }
}

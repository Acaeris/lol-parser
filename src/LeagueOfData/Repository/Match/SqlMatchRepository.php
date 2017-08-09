<?php

namespace LeagueOfData\Repository\Match;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\Match;
use LeagueOfData\Entity\Match\MatchInterface;
use LeagueOfData\Repository\Match\SqlMatchPlayerRepository;

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
     * @var SqlMatchPlayerRepository
     */
    private $playerRepository;

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
        LoggerInterface $logger,
        SqlMatchPlayerRepository $playerRepository
    ) {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
        $this->playerRepository = $playerRepository;
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
     * @param  array $match
     * @return EntityInterface
     */
    public function create(array $match): EntityInterface
    {
        $players = $this->playerRepository->fetch(
            "SELECT * FROM match_players WHERE region = :region AND match_id = :match_id",
            $match
        );

        return new Match(
            $match['match_id'],
            $match['match_mode'],
            $match['match_type'],
            $match['map_id'] ?? 0,
            $match['duration'],
            $players,
            $match['version'],
            $match['region'],
            $match['season_id'] ?? 0
        );
    }

    /**
     * Fetch Match
     *
     * @param  string $query SQL Query
     * @param  array  $where SQL Where parameters
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
            $this->playerRepository->add($match->getPlayers());

            if ($this->dbConn->fetchAll($select, $match->getKeyData())) {
                $this->dbConn->update('matches', $this->convertMatchToArray($match), $match->getKeyData());
                continue;
            }
            $this->dbConn->insert('matches', $this->convertMatchToArray($match));
        }

        $this->playerRepository->store();
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
     * @param  MatchInterface $match
     * @return array
     */
    private function convertMatchToArray(MatchInterface $match): array
    {
        return [
            'match_id' => $match->getMatchID(),
            'match_mode' => $match->getMode(),
            'match_type' => $match->getType(),
            'duration' => $match->getDuration(),
            'map_id' => $match->getMapID(),
            'season_id' => $match->getSeasonID(),
            'version' => $match->getVersion(),
            'region' => $match->getRegion()
        ];
    }
}

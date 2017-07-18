<?php

namespace LeagueOfData\Repository\Rune;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Rune\Rune;
use LeagueOfData\Entity\Stat;

/**
 * Rune object DB Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlRuneRepository implements StoreRepositoryInterface
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
     * @var array Runes
     */
    private $runes;

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add rune objects to internal array
     *
     * @param array $runes Rune objects
     */
    public function add(array $runes)
    {
        foreach ($runes as $rune) {
            $this->runes[$rune->getRuneID()] = $rune;
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->runes = [];
    }

    /**
     * Build the Rune object from the given data.
     *
     * @param array $rune
     * @return EntityInterface
     */
    public function create(array $rune): EntityInterface
    {
        return new Rune(
            $rune['rune_id'],
            $rune['rune_name'],
            $rune['description'],
            $rune['image_name'],
            $rune['stats'],
            explode('|', $rune['tags']),
            $rune['version'],
            $rune['region']
        );
    }

    /**
     * Fetch Runes
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array Rune Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->runes = [];

        $this->logger->debug('Fetching runes from DB');

        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);

        $this->logger->debug(count($this->runes)." runes fetched from DB");

        return $this->runes;
    }

    /**
     * Store the rune objects in the DB
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->runes)." new/updated runes");

        foreach ($this->runes as $rune) {
            $select = "SELECT rune_id FROM runes WHERE rune_id = :rune_id AND version = :version AND region = :region";

            $this->storeStats($rune);

            if ($this->dbConn->fetchAll($select, $rune->getKeyData())) {
                $this->dbConn->update('runes', $this->convertRuneToArray($rune), $rune->getKeyData());
                continue;
            }

            $this->dbConn->insert('runes', $this->convertRuneToArray($rune));
        }
    }

    /**
     * Transfer objects out to another repository
     *
     * @return array Rune objects
     */
    public function transfer(): array
    {
        return $this->runes;
    }

    /**
     * Convert result data into Rune objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $rune) {
                $rune['stats'] = $this->fetchStats($rune);
                $this->runes[$rune['rune_id']] = $this->create($rune);
            }
        }
    }

    /**
     * Fetch the stats for the given rune
     *
     * @param array $rune
     * @return array
     */
    private function fetchStats(array $rune) : array
    {
        $select = "SELECT * FROM rune_stats WHERE rune_id = :rune_id AND version = :version AND region = :region";
        $where = ['rune_id' => $rune['rune_id'], 'version' => $rune['version'], 'region' => $rune['region']];
        $stats = [];
        $results = $this->dbConn->fetchAll($select, $where);

        if ($results !== false) {
            foreach ($results as $stat) {
                $stats[] = new Stat($stat['stat_name'], $stat['stat_value']);
            }
        }

        return $stats;
    }

    /**
     * Store the rune stats in the database
     *
     * @param Rune $rune
     */
    private function storeStats(Rune $rune)
    {
        foreach ($rune->getStats() as $stat) {
            $select = "SELECT rune_id FROM rune_stats WHERE rune_id = :rune_id AND version = :version "
                . "AND stat_name = :stat_name AND region = :region";
            $where = $rune->getKeyData();
            $where['stat_name'] = $stat->getStatName();
            $data = array_merge($where, ['stat_value' => $stat->getStatModifier()]);

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('rune_stats', $data, $where);
                continue;
            }

            $this->dbConn->insert('rune_stats', $data);
        }
    }

    /**
     * Converts Rune object into SQL data array
     *
     * @param Rune $rune
     * @return array
     */
    private function convertRuneToArray(Rune $rune) : array
    {
        return [
            'rune_id' => $rune->getRuneID(),
            'rune_name' => $rune->getName(),
            'description' => $rune->getDescription(),
            'image_name' => $rune->getImageName(),
            'tags' => implode('|', $rune->getTags()),
            'version' => $rune->getVersion(),
            'region' => $rune->getRegion()
        ];
    }
}

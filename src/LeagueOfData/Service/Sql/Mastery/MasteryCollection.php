<?php

namespace LeagueOfData\Service\Sql\Mastery;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Mastery\Mastery;
use LeagueOfData\Entity\Stat;

/**
 * Mastery object SQL factory.
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class MasteryCollection implements StoreServiceInterface
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
     * @var array Masteries
     */
    private $masteries;

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add mastery objects to internal array
     *
     * @param array $masteries Mastery objects
     */
    public function add(array $masteries)
    {
        foreach ($masteries as $mastery) {
            $this->masteries[$mastery->getRuneID()] = $mastery;
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->masteries = [];
    }

    /**
     * Build the Mastery object from the given data.
     *
     * @param array $mastery
     * @return EntityInterface
     */
    public function create(array $mastery): EntityInterface
    {
        return new Mastery(
            $mastery['rune_id'],
            $mastery['rune_name'],
            $mastery['description'],
            $mastery['image_name'],
            $mastery['stats'],
            explode('|', $mastery['tags']),
            $mastery['version'],
            $mastery['region']
        );
    }

    /**
     * Fetch Runes
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array Mastery Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->masteries = [];

        $this->logger->debug('Fetching masteries from DB');

        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);

        $this->logger->debug(count($this->masteries)." masteries fetched from DB");

        return $this->masteries;
    }

    /**
     * Store the mastery objects in the DB
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->masteries)." new/updated masteries");

        foreach ($this->masteries as $mastery) {
            $select = "SELECT mastery_id FROM masteries WHERE mastery_id = :mastery_id AND version = :version"
                . " AND region = :region";

            $this->storeStats($mastery);

            if ($this->dbConn->fetchAll($select, $mastery->getKeyData())) {
                $this->dbConn->update('masteries', $this->convertMasteryToArray($mastery), $mastery->getKeyData());
                continue;
            }

            $this->dbConn->insert('masteries', $this->convertMasteryToArray($mastery));
        }
    }

    /**
     * Transfer objects out to another service
     *
     * @return array Mastery objects
     */
    public function transfer(): array
    {
        return $this->masteries;
    }

    /**
     * Convert result data into Rune objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $mastery) {
                $mastery['stats'] = $this->fetchStats($mastery);
                $this->masteries[$mastery['mastery_id']] = $this->create($mastery);
            }
        }
    }

    /**
     * Fetch the stats for the given mastery
     *
     * @param array $mastery
     * @return array
     */
    private function fetchStats(array $mastery) : array
    {
        $select = "SELECT * FROM mastery_stats WHERE mastery_id = :mastery_id AND version = :version"
            . " AND region = :region";
        $where = ['mastery_id' => $mastery['mastery_id'], 'version' => $mastery['version'],
            'region' => $mastery['region']];
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
     * Store the mastery stats in the database
     *
     * @param Mastery $mastery
     */
    private function storeStats(Mastery $mastery)
    {
        foreach ($mastery->getStats() as $stat) {
            $select = "SELECT mastery_id FROM mastery_stats WHERE mastery_id = :mastery_id AND version = :version "
                . "AND stat_name = :stat_name AND region = :region";
            $where = $mastery->getKeyData();
            $where['stat_name'] = $stat->getStatName();
            $data = array_merge($where, ['stat_value' => $stat->getStatModifier()]);

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('mastery_stats', $data, $where);
                continue;
            }

            $this->dbConn->insert('mastery_stats', $data);
        }
    }

    /**
     * Converts Mastery object into SQL data array
     *
     * @param Mastery $mastery
     * @return array
     */
    private function convertMasteryToArray(Mastery $mastery) : array
    {
        return [
            'mastery_id' => $mastery->getMasteryID(),
            'mastery_name' => $mastery->getName(),
            'description' => $mastery->getDescription(),
            'image_name' => $mastery->getImageName(),
            'tags' => implode('|', $mastery->getTags()),
            'version' => $mastery->getVersion(),
            'region' => $mastery->getRegion()
        ];
    }
}

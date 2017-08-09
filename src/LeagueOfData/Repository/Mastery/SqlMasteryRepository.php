<?php

namespace LeagueOfData\Repository\Mastery;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Mastery\Mastery;

/**
 * Mastery object DB Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlMasteryRepository implements StoreRepositoryInterface
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
            $this->masteries[$mastery->getMasteryID()] = $mastery;
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
     * @param  array $mastery
     * @return EntityInterface
     */
    public function create(array $mastery): EntityInterface
    {
        return new Mastery(
            $mastery['mastery_id'],
            $mastery['mastery_name'],
            explode('|', $mastery['description']),
            $mastery['ranks'],
            $mastery['image_name'],
            $mastery['mastery_tree'],
            $mastery['version'],
            $mastery['region']
        );
    }

    /**
     * Fetch Masteries
     *
     * @param  string $query SQL Query
     * @param  array  $where SQL Where parameters
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

            if ($this->dbConn->fetchAll($select, $mastery->getKeyData())) {
                $this->dbConn->update('masteries', $this->convertMasteryToArray($mastery), $mastery->getKeyData());
                continue;
            }

            $this->dbConn->insert('masteries', $this->convertMasteryToArray($mastery));
        }
    }

    /**
     * Transfer objects out to another repository
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
                $this->masteries[$mastery['mastery_id']] = $this->create($mastery);
            }
        }
    }

    /**
     * Converts Mastery object into SQL data array
     *
     * @param  Mastery $mastery
     * @return array
     */
    private function convertMasteryToArray(Mastery $mastery) : array
    {
        return [
            'mastery_id' => $mastery->getMasteryID(),
            'mastery_name' => $mastery->getName(),
            'description' => implode('|', $mastery->getDescription()),
            'ranks' => $mastery->getRanks(),
            'image_name' => $mastery->getImageName(),
            'mastery_tree' => $mastery->getMasteryTree(),
            'version' => $mastery->getVersion(),
            'region' => $mastery->getRegion()
        ];
    }
}

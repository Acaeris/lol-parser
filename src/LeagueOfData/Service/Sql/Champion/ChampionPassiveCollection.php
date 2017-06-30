<?php

namespace LeagueOfData\Service\Sql\Champion;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;
use LeagueOfData\Entity\Champion\ChampionPassive;

/**
 * Champion Passive object SQL factory
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionPassiveCollection implements StoreServiceInterface
{
    /**
     * @var Connection
     */
    private $dbConn;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $passives;

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dbConn = $dbConn;
    }

    /**
     * Add all champion passive objects to internal array
     *
     * @param array $passives ChampionPassive objects
     */
    public function add(array $passives)
    {
        foreach ($passives as $passive) {
            if ($passive instanceof ChampionPassiveInterface) {
                $this->passives[$passive->getChampionID()] = $passive;
                continue;
            }
            $this->logger->error('Incorrect object supplied to Champion Passives service', [$passive]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->passives = [];
    }

    /**
     * Factory to create Champion Passive objects from SQL
     *
     * @param array $passive
     * @return EntityInterface
     */
    public function create(array $passive) : EntityInterface
    {
        return new ChampionPassive(
            $passive['champion_id'],
            $passive['passive_name'],
            $passive['image_name'],
            $passive['description'],
            $passive['version'],
            $passive['region']
        );
    }

    /**
     * Fetch Champions Passives
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array ChampionPassive Objects
     */
    public function fetch(string $query, array $where = []) : array
    {
        $this->logger->debug('Fetch champion passives from DB');
        $results = $this->dbConn->fetchAll($query, $where);
        $this->passives = [];
        $this->processResults($results);
        $this->logger->debug(count($this->passives)." passives fetch from DB");

        return $this->passives;
    }

    /**
     * Store the champion passives in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->passives)." new/updated spells");

        foreach ($this->passives as $passive) {
            $select = 'SELECT passive_name FROM champion_passives WHERE champion_id = :champion_id'
                . ' AND version = :version AND passive_name = :passive_name AND region = :region';

            if ($this->dbConn->fetchAll($select, $passive->getKeyData())) {
                $this->dbConn->update('champion_passives', $this->convertPassiveToArray($passive),
                    $passive->getKeyData());

                continue;
            }

            $this->dbConn->insert('champion_passives', $this->convertPassiveToArray($passive));
        }
    }

    /**
     * Get collection of champions' passives for transfer to a different process.
     *
     * @return array ChampionPassive objects
     */
    public function transfer() : array
    {
        return $this->passives;
    }

    /**
     * Convert result data into ChampionPassives objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $passive) {
                $this->passives[$passive['champion_id']] = $this->create($passive);
            }
        }
    }

    /**
     * Converts Passive object into SQL data array
     *
     * @param ChampionPassive $passive
     * @return array
     */
    private function convertPassiveToArray(ChampionPassive $passive) : array
    {
        return [
            'champion_id' => $passive->getChampionID(),
            'passive_name' => $passive->getPassiveName(),
            'image_name' => $passive->getImageName(),
            'description' => $passive->getDescription(),
            'version' => $passive->getVersion(),
            'region' => $passive->getRegion()
        ];
    }
}

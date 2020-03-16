<?php

namespace App\Services\Sql\Realm;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use App\Services\StoreServiceInterface;
use App\Models\EntityInterface;
use App\Models\Realm\Realm;
use App\Models\Realm\RealmInterface;

/**
 * Realm object SQL factory.
 *
 * @package LeagueOfData\Services\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class RealmCollection implements StoreServiceInterface
{
    /**
     * @var Connection DB connection
     */
    private $dbConn;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Realm objects
     */
    private $realms = [];

    /**
     * Setup Realm factory service
     *
     * @param Connection      $connection
     * @param LoggerInterface $logger
     */
    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->dbConn = $connection;
        $this->log = $logger;
    }

    /**
     * Add realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function add(array $realms)
    {
        foreach ($realms as $realm) {
            if ($realm instanceof RealmInterface) {
                $this->realms[$realm->getVersion()] = $realm;
                continue;
            }
            $this->log->error('Incorrect object supplied to Realm service', [$realm]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->realms = [];
    }

    /**
     * Factory to create realm objects
     *
     * @param array $realm
     * @return EntityInterface
     */
    public function create(array $realm) : EntityInterface
    {
        return new Realm($realm['cdn'], $realm['version']);
    }

    /**
     * Find all Realm data
     *
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array Realm objects
     */
    public function fetch(string $query, array $where = []) : array
    {
        $this->realms = [];
        $this->processResults($this->dbConn->fetchAll($query, $where));

        return $this->realms;
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        foreach ($this->realms as $realm) {
            $select = 'SELECT version FROM realms WHERE version = :version';

            if ($this->dbConn->fetchAll($select, $realm->getKeyData())) {
                $this->dbConn->update('realms', $this->convertRealmToArray($realm), $realm->getKeyData());
                continue;
            }

            $this->dbConn->insert('realms', $this->convertRealmToArray($realm));
        }
    }

    /**
     * Get collection of realms for transfer to a different process
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->realms;
    }

    /**
     * Convert result data into Realm objects
     *
     * @param array $results
     * @throws \Exception
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                throw new \Exception('Realms Results in unexpected format.');
            }

            foreach ($results as $realm) {
                $this->realms[$realm['version']] = $this->create($realm);
            }
        }
    }

    /**
     * Converts Realm object into SQL data array
     *
     * @param Realm $realm
     * @return array
     */
    private function convertRealmToArray(Realm $realm) : array
    {
        return [
            'version' => $realm->getVersion(),
            'cdn' => $this->getSourceUrl()
        ];
    }
}

<?php

namespace LeagueOfData\Service\Sql\Version;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Version\Version;
use LeagueOfData\Entity\Version\VersionInterface;

/**
 * Version object SQL factory.
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class VersionCollection implements StoreServiceInterface
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
     * @var array Version objects
     */
    private $versions = [];

    /**
     * Setup version factory service
     *
     * @param Connection      $connection
     * @param LoggerInterface $log
     */
    public function __construct(Connection $connection, LoggerInterface $log)
    {
        $this->dbConn = $connection;
        $this->log = $log;
    }

    /**
     * Add version objects to internal array
     *
     * @param array $versions Version objects
     */
    public function add(array $versions)
    {
        foreach ($versions as $version) {
            if ($version instanceof VersionInterface) {
                $this->versions[$version->getFullVersion()] = $version;
                continue;
            }
            $this->log->error('Incorrect object supplied to Version service', [$version]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->versions = [];
    }

    /**
     * Factory for creating version objects
     *
     * @param string $version
     * @return EntityInterface
     */
    public function create(array $version) : EntityInterface
    {
        return new Version($version['full_version']);
    }

    /**
     * Fetch Version data
     *
     * @param string $query SQL Query
     * @param array  $where SQL parameters
     * @return array Version objects
     */
    public function fetch(string $query, array $where = []) : array
    {
        $this->log->debug("Fetch versions from DB");
        $this->versions = [];
        $results = $this->dbConn->fetchAll($query, $where);
        $this->processResults($results);
        $this->log->debug(count($this->versions)." versions fetch from DB");

        return $this->versions;
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->versions)." new/updated versions");

        foreach ($this->versions as $version) {
            $select = 'SELECT full_version FROM versions WHERE full_version = :full_version';

            if ($this->dbConn->fetchAll($select, $version->getKeyData())) {
                $this->dbConn->update('versions', $this->convertVersionToArray($version), $version->getKeyData());
                continue;
            }

            $this->dbConn->insert('versions', $this->convertVersionToArray($version));
        }
    }

    /**
     * Transfer objects out to another service
     *
     * @return array Version objects
     */
    public function transfer() : array
    {
        return $this->versions;
    }

    /**
     * Convert result data into version objects
     *
     * @param array $results
     * @throws \Exception
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $version) {
                $this->versions[$version['full_version']] = $this->create($version);
            }
        }
    }

    /**
     * Converts Version object into SQL data array
     *
     * @param VersionInterface $version
     * @return array
     */
    private function convertVersionToArray(VersionInterface $version) : array
    {
        return [
            'full_version' => $version->getFullVersion(),
            'season' => $version->getSeason(),
            'version' => $version->getMajorVersion(),
            'hotfix' => $version->getHotfix(),
        ];
    }
}

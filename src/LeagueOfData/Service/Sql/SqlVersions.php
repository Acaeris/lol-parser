<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Adapters\Request;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Service\Interfaces\VersionServiceInterface;
use LeagueOfData\Models\Version;
use LeagueOfData\Models\Interfaces\VersionInterface;

/**
 * Version object SQL factory.
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlVersions implements VersionServiceInterface
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
     * Factory for creating version objects
     *
     * @param string $version
     * @return VersionInterface
     */
    public function create(array $version) : VersionInterface
    {
        return new Version($version['full_version']);
    }

    /**
     * Fetch Version data
     *
     * @param RequestInterface $request
     * @return array Version objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetch versions from DB");
        $this->versions = [];
        $request->requestFormat(Request::TYPE_SQL);
        $results = $this->dbConn->fetchAll($request->query(), $request->where());
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
            $where = [ 'full_version' => $version->getFullVersion() ];
            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('versions', $version->toArray(), $where);
            } else {
                $this->dbConn->insert('versions', $version->toArray());
            }
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
}

<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Service\Interfaces\VersionServiceInterface;
use LeagueOfData\Models\Version;
use LeagueOfData\Models\Interfaces\VersionInterface;
use LeagueOfData\Adapters\Request\VersionRequest;

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
     * @var AdapterInterface DB adapter
     */
    private $dbAdapter;
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
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->dbAdapter = $adapter;
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
        $results = $this->dbAdapter->fetch($request);
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
            $request = new VersionRequest(
                [ 'full_version' => $version->getFullVersion() ],
                'full_version',
                $version->toArray()
            );
            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);
            } else {
                $this->dbAdapter->insert($request);
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

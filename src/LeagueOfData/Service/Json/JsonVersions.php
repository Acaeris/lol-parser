<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Version;
use LeagueOfData\Models\Interfaces\VersionInterface;
use LeagueOfData\Service\Interfaces\VersionServiceInterface;

/**
 * Version object JSON factory.
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonVersions implements VersionServiceInterface
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
     * Set up version service
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
    public function create(string $version) : VersionInterface
    {
        return new Version($version);
    }

    /**
     * Find all Version data
     *
     * @param RequestInterface $request
     * @return array Version objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetch versions from API");
        $response = $this->dbAdapter->fetch($request);
        $this->versions = [];
        $this->processResults($response);
        $this->log->debug(count($this->versions)." versions fetch from API");

        return $this->versions;
    }

    /**
     * Collection of Version objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->versions;
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        $this->log->error("Attempting to store data via API. Not available.");
    }

    /**
     * Convert result data into version objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $version) {
                $this->versions[] = $this->create($version);
            }
        }
    }
}

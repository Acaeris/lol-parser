<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Version;
use LeagueOfData\Service\Interfaces\VersionServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use Psr\Log\LoggerInterface;

/**
 * Version object JSON factory.
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonVersions implements VersionServiceInterface
{
    /* @var AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var LoggerInterface Logger */
    private $log;
    /* @var array Version objects */
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
     * Find all Version data
     *
     * @return array Version objects
     */
    public function fetch() : array
    {
        $request = new VersionRequest([]);
        $response = $this->dbAdapter->fetch($request);
        $this->versions = [];

        foreach ($response as $version) {
            $this->versions[] = new Version($version);
        }

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
     * Add a version object to internal array
     *
     * @param Version $version
     */
    public function add(Version $version)
    {
        $this->versions[] = $version;
    }

    /**
     * Add all version objects to internal array
     *
     * @param array $versions Version objects
     */
    public function addAll(array $versions)
    {
        $this->versions = array_merge($this->versions, $versions);
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        $this->log->error("Attempting to store data via API. Not available.");
    }
}

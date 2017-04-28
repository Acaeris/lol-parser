<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\VersionServiceInterface;
use LeagueOfData\Models\Version;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use Psr\Log\LoggerInterface;

/**
 * Version object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlVersions implements VersionServiceInterface
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Version objects */
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
        $this->versions = array_merge($this->versions, $versions);
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        foreach ($this->versions as $version) {
            $request = new VersionRequest(
                [ 'full_version' => $version->fullVersion() ],
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
     * Fetch Version data
     *
     * @return array Version objects
     */
    public function fetch() : array
    {
        $this->versions = [];
        $request = new VersionRequest([], 'full_version');
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            foreach ($results as $version) {
                $this->versions[] = new Version($version);
            }
        }

        return $this->versions;
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
}

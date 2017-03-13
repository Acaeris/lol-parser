<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\VersionService;
use LeagueOfData\Models\Version;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use Psr\Log\LoggerInterface;

final class SqlVersions implements VersionService
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $db;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Version objects */
    private $versions = [];

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->db = $adapter;
        $this->log = $log;
    }

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
        foreach ($this->versions as $version) {
            $request = new VersionRequest([ 'fullversion' => $version->fullVersion() ],
                'SELECT fullversion FROM version WHERE fullversion = :fullversion', $version->toArray());
            if ($this->db->fetch($request)) {
                $this->db->update($request);
            } else {
                $this->db->insert($request);
            }
        }
    }

    /**
     * Find all Version data
     *
     * @return array Version objects
     */
    public function findAll()
    {
        $this->versions = [];
        $request = new VersionRequest([], 'SELECT fullversion FROM version');
        $results = $this->db->fetch($request);
        if ($results !== false) {
            foreach ($results as $version) {
                $this->versions[] = new Version($version);
            }
        }
        return $this->versions;
    }

    public function transfer()
    {
        return $this->versions;
    }
}

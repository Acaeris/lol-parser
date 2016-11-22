<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\VersionService;
use LeagueOfData\Models\Version;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use Psr\Log\LoggerInterface;

final class SqlVersions implements VersionService
{
    private $db;
    private $log;
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

    public function addAll($versions)
    {
        $this->versions = array_merge($this->versions, $versions);
    }

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

    public function findAll()
    {
        $this->versions = [];
        $request = new VersionRequest([], 'SELECT * FROM version');
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
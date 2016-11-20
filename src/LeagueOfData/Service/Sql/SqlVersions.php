<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\VersionService;
use LeagueOfData\Models\Version;
use LeagueOfData\Adapters\AdapterInterface;
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
            if ($this->db->fetch('version', [
                'query' => 'SELECT fullversion FROM version WHERE fullversion = ?',
                'params' => [ $version->fullVersion() ]
            ])) {
                $this->db->update('version', $version->toArray(), [
                    'fullVersion' => $version->fullVersion()
                ]);
            } else {
                $this->db->insert('version', $version->toArray());
            }
        }
    }

    public function findAll()
    {
        $this->versions = [];
        $results = $this->db->fetch('version', [
            'query' => 'SELECT * FROM version',
            'params' => []
        ]);
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
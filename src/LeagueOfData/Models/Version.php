<?php

namespace LeagueOfData\Models;

use LeagueOfData\Adapters\AdapterInterface;

final class Version implements VersionInterface
{
    private $fullVersion;
    private $season;
    private $version;
    private $hotfix;

    public function __construct($data) {
        $parts = explode('.', $data);
        $this->fullVersion = $data;
        $this->season = $parts[0];
        $this->version = $parts[1];
        $this->hotfix = $parts[2];
    }

    public function versionNumber() {
        return $this->fullVersion;
    }

    public function toArray() {
        return [
            'fullVersion' => $this->fullVersion,
            'season' => $this->season,
            'version' => $this->version,
            'hotfix' => $this->hotfix
        ];
    }

    public function store(AdapterInterface $adapter)
    {
        if ($adapter->fetch('version', [
            'query' => 'SELECT fullVersion FROM leagueOfData.version WHERE fullVersion = ?',
            'params' => [ $this->fullVersion ]
        ])) {
            $adapter->update('version', $this->toArray(), [ 'fullVersion' => $this->fullVersion ]);
        } else {
            $adapter->insert('version', $this->toArray());
        }
    }
}

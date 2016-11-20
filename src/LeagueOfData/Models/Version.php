<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\Version as VersionInterface;

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

    public function fullVersion() {
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
}

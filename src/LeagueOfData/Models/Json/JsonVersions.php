<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\Version;
use LeagueOfData\Models\Versions;
use LeagueOfData\Adapters\AdapterInterface;

final class JsonVersions implements Versions
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function collectAll() {
        $response = $this->source->fetch('version', []);
        $versions = [];
        foreach ($response as $version) {
            $versions[] = new Version($version);
        }
        return $versions;
    }
}

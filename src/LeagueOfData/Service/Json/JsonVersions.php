<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Version;
use LeagueOfData\Service\Interfaces\VersionService;
use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

final class JsonVersions implements VersionService
{
    private $source;
    private $log;
    private $versions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    public function findAll()
    {
        $response = $this->source->fetch('version', []);
        $this->versions = [];
        foreach ($response as $version) {
            $this->versions[] = new Version($version);
        }
        return $this->versions;
    }

    public function transfer()
    {
        return $this->versions;
    }
}

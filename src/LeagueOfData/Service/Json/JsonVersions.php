<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Version;
use LeagueOfData\Service\Interfaces\VersionServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use Psr\Log\LoggerInterface;

final class JsonVersions implements VersionServiceInterface
{
    private $source;
    private $log;
    private $versions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    /**
     * Find all Version data
     *
     * @return array Version objects
     */
    public function findAll() : array
    {
        $request = new VersionRequest([]);
        $response = $this->source->fetch($request);
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
}

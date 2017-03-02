<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\RealmService;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;

use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

final class JsonRealms implements RealmService
{
    /* @var array Available Regions */
    const REGIONS = ['euw', 'eune', 'na'];

    private $source;
    private $log;
    private $realms;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    /**
     * Find all Realm data
     *
     * @return array Realm objects
     */
    public function findAll() : array
    {
        $this->realms = [];
        foreach (self::REGIONS as $region) {
            $request = new RealmRequest(['region' => $region]);
            $response = $this->source->fetch($request);
            $this->realms[] = new Realm($realm['cdn'], $realm['v'], $region);
        }
        return $this->realms;
    }
}

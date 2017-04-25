<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;

use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

final class JsonRealms implements RealmServiceInterface
{
    /* @var array Available Regions */
    const REGIONS = ['euw', 'eune', 'na'];

    /* @var LeagueOfData\Adapters\AdapterInterface API adapter */
    private $source;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Realm objects */
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
            $this->realms[] = new Realm($response['cdn'], $response['v'], $region);
        }
        return $this->realms;
    }
}

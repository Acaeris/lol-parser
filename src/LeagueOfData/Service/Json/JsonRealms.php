<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\RealmService;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;

use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

final class JsonRealms implements RealmService
{
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
        $request = new RealmRequest([]);
        $response = $this->source->fetch($request);
        $this->realms = [];
        foreach ($response as $realm) {
            $this->realms[] = new Realm($realm['cdn'], $realm['v'], 'euw');
        }
        return $this->realms;
    }
}

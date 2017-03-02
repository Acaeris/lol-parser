<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\RealmService;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

class SqlRealms implements RealmService
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $db;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Realm objects */
    private $realms;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->db = $adapter;
        $this->log = $logger;
    }

    /**
     * Find all Realm data
     *
     * @return array Realm objects
     */
    public function findAll() : array
    {
        $this->realms = [];
        $request = new RealmRequest([], 'SELECT `cdn`, `region`, MAX(`version`) FROM realm GROUP BY `region`');
        $response = $this->db->fetch($request);
        foreach ($response as $realm) {
            $this->realms[] = new Realm($realm['cdn'], $realm['version'], $realm['region']);
        }
        return $this->realms;
    }

    /**
     * Add all realm objects to internal array
     *
     * @param array Realm objects
     */
    public function addAll(array $realms) {
        $this->realms = array_merge($this->realms, $realms);
    }
}

<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

/**
 * Realm object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlRealms implements RealmServiceInterface
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $db;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Realm objects */
    private $realms = [];

    /**
     * Setup Realm factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $logger
     */
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
    public function fetch() : array
    {
        $this->realms = [];
        $request = new RealmRequest([], 'SELECT `cdn`, `version` FROM realms ORDER BY `version` DESC LIMIT 1');
        $response = $this->db->fetch($request);

        foreach ($response as $realm) {
            $this->realms[] = new Realm($realm['cdn'], $realm['version']);
        }

        return $this->realms;
    }

    /**
     * Add realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function add(array $realms)
    {
        $this->realms = array_merge($this->realms, $realms);
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        foreach ($this->realms as $realm) {
            $request = new RealmRequest(
                [
                    'version' => $realm->version(),
                ],
                'SELECT version FROM realms WHERE version = :version',
                $realm->toArray()
            );
            if ($this->db->fetch($request)) {
                $this->db->update($request);
            } else {
                $this->db->insert($request);
            }
        }
    }
}

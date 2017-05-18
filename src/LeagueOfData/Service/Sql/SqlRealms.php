<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use LeagueOfData\Models\Interfaces\RealmInterface;

/**
 * Realm object SQL factory.
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlRealms implements RealmServiceInterface
{
    /**
     * @var AdapterInterface DB adapter
     */
    private $db;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Realm objects
     */
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
     * Add realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function add(array $realms)
    {
        foreach ($realms as $realm) {
            if ($realm instanceof RealmInterface) {
                $this->realms[$realm->getVersion()] = $realm;
                continue;
            }
            $this->log->error('Incorrect object supplied to Realm service', [$realm]);
        }
    }

    /**
     * Factory to create realm objects
     *
     * @param array $realm
     * @return RealmInterface
     */
    public function create(array $realm) : RealmInterface
    {
        return new Realm($realm['cdn'], $realm['version']);
    }
    
    /**
     * Find all Realm data
     *
     * @param RequestInterface $request
     * @return array Realm objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->realms = [];
        $response = $this->db->fetch($request);
        $this->processResults($response);

        return $this->realms;
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

    /**
     * Get collection of realms for transfer to a different process
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->realms;
    }

    /**
     * Convert result data into Realm objects
     *
     * @param array $results
     * @throws \Exception
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                throw new \Exception('Realms Results in unexpected format.');
            }

            foreach ($results as $realm) {
                $this->realms[$realm['version']] = $this->create($realm);
            }    
        }
    }
}

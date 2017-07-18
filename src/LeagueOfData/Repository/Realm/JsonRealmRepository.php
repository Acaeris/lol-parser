<?php

namespace LeagueOfData\Repository\Realm;

use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Realm\Realm;

/**
 * Realm object API Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonRealmRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = [ 'region' => 'euw' ];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'static-data/v3/realms';

    /**
     * @var AdapterInterface API adapter
     */
    private $source;

    /**
     * @var LoggerInterface Logger
     */
    private $log;

    /**
     * @var array Realm objects
     */
    private $realms;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    /**
     * Factory for creating Realm objects
     *
     * @param array $realm
     * @return EntityInterface
     */
    public function create(array $realm) : EntityInterface
    {
        return new Realm($realm['cdn'], $realm['v']);
    }

    /**
     * Find all Realm data
     *
     * @param array $params API parameters
     * @return array Realm objects
     */
    public function fetch(array $params) : array
    {
        $this->realms = [];
        $response = $this->source->fetch($this->apiEndpoint, array_merge($this->apiDefaults, $params));
        $this->realms[$response['v']] = new Realm($response['cdn'], $response['v']);
        return $this->realms;
    }

    /**
     * Collection of Realm objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->realms;
    }
}

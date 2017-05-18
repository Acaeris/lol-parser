<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Realm;
use LeagueOfData\Models\Interfaces\RealmInterface;

/**
 * Realm object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonRealms implements RealmServiceInterface
{
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
     * Add all realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function add(array $realms)
    {
        foreach ($realms as $realm) {
            $this->realms[$realm->getVersion()] = $realm;
        }
    }

    /**
     * Factory for creating Realm objects
     *
     * @param array $realm
     * @return RealmInterface
     */
    public function create(array $realm) : RealmInterface
    {
        return new Realm($realm['cdn'], $realm['v']);
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
        $response = $this->source->fetch($request);
        $this->realms[$response['v']] = new Realm($response['cdn'], $response['v']);
        return $this->realms;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        throw new \Exception("Request to store data through JSON API not available.");
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

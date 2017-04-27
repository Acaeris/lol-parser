<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

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
     * Add a realm to the collection
     *
     * @param Realm $realm
     */
    public function add(Realm $realm)
    {
        $this->realms[] = $realm;
    }

    /**
     * Add all realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function addAll(array $realms)
    {
        foreach ($realms as $realm) {
            $this->realms[] = $realm;
        }
    }

    /**
     * Find all Realm data
     *
     * @return array Realm objects
     */
    public function fetch() : array
    {
        $this->realms = [];
        $request = new RealmRequest([]);
        $response = $this->source->fetch($request);
        $this->realms[] = new Realm($response['cdn'], $response['v']);
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

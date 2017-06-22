<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Realm\Realm;

/**
 * Realm object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonRealms implements FetchServiceInterface
{
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
     * Collection of Realm objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->realms;
    }
}

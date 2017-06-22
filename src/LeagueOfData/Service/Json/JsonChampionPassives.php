<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionPassive;

/**
 * Champion Passives object JSON factory
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampionPassives implements FetchServiceInterface
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var array
     */
    private $passives;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->adapter = $adapter;
    }

    /**
     * Factory to create Champion Passive object from JSON
     *
     * @param array $champion
     * @return EntityInterface
     */
    public function create(array $champion): EntityInterface
    {
        return new ChampionPassive(
            $champion['id'],
            $champion['passive']['name'],
            $champion['passive']['image']['full'],
            $champion['passive']['description'],
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champion passives
     *
     * @param RequestInterface $request
     * @throws Exception
     */
    public function fetch(RequestInterface $request): array
    {
        throw new \Exception("Fetch method for Champion Passives from API is not currently implemented");
    }

    /**
     * Get collection of champions' passive for transfer to a different process.
     *
     * @return array ChampionStats objects
     */
    public function transfer(): array
    {
        return $this->passives;
    }
}

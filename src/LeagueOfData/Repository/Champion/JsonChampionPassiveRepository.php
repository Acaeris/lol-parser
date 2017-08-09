<?php

namespace LeagueOfData\Repository\Champion;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionPassive;

/**
 * Champion Passives object API repository
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonChampionPassiveRepository implements FetchRepositoryInterface
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
     * @param  array $champion
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
     * @param  array $params API Parameters
     * @throws Exception
     */
    public function fetch(array $params): array
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

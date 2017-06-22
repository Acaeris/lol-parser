<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Champion\ChampionStats;
use LeagueOfData\Entity\Champion\ChampionDefense;
use LeagueOfData\Entity\Champion\ChampionRegenResource;
use LeagueOfData\Entity\Champion\ChampionAttack;
use LeagueOfData\Entity\Champion\ChampionRegenResourceInterface;

/**
 * Champion Stats object JSON factory
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampionStats implements FetchServiceInterface
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
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->adapter = $adapter;
    }

    /**
     * Factory to create Champion Stats objects from JSON
     *
     * @param array $champion
     * @return EntityInterface
     */
    public function create(array $champion) : EntityInterface
    {
        return new ChampionStats(
            $champion['id'],
            $this->createHealth($champion),
            $this->createResource($champion),
            $this->createAttack($champion),
            $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $champion),
            $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $champion),
            $champion['stats']['movespeed'],
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champions Stats
     *
     * @param RequestInterface $request
     * @return array ChampionStats Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        throw new \Exception("Fetch method for Champion Stats from API is not currently implemented");
    }

    /**
     * Get collection of champions' stats for transfer to a different process.
     *
     * @return array ChampionStats objects
     */
    public function transfer() : array
    {
        return $this->champions;
    }

    /**
     * Create Champion Health object
     *
     * @param array $champion
     * @return ChampionRegenResourceInterface
     */
    private function createHealth(array $champion) : ChampionRegenResourceInterface
    {
        return new ChampionRegenResource(
            $champion['stats']['hp'],
            $champion['stats']['hpperlevel'],
            $champion['stats']['hpregen'],
            $champion['stats']['hpregenperlevel']
        );
    }

    /**
     * Create Champion Resource object
     *
     * @param string $type
     * @param array  $champion
     * @return ChampionRegenResourceInterface
     */
    private function createResource(array $champion) : ChampionRegenResourceInterface
    {
        return new ChampionRegenResource(
            $champion['stats']['mp'],
            $champion['stats']['mpperlevel'],
            $champion['stats']['mpregen'],
            $champion['stats']['mpregenperlevel']
        );
    }

    /**
     * Create Champion Defense object
     *
     * @param string $type
     * @param array  $champion
     *
     * @return ChampionDefense
     */
    private function createDefense(string $type, array $champion) : ChampionDefense
    {
        return new ChampionDefense(
            $type,
            $champion['stats']['armor'],
            $champion['stats']['armorperlevel']
        );
    }

    /**
     * Create Champion Attack object
     *
     * @param array $champion
     *
     * @return ChampionAttack
     */
    private function createAttack(array $champion) : ChampionAttack
    {
        return new ChampionAttack(
            $champion['stats']['attackrange'],
            $champion['stats']['attackdamage'],
            $champion['stats']['attackdamageperlevel'],
            $champion['stats']['attackspeedoffset'],
            $champion['stats']['attackspeedperlevel'],
            $champion['stats']['crit'],
            $champion['stats']['critperlevel']
        );
    }
}

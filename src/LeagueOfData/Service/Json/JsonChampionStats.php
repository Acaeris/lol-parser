<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;

/**
 * Champion Stats object JSON factory
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampionStats implements ChampionStatsServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Factory to create Champion Stats objects from JSON
     *
     * @param array $champion
     *
     * @return ChampionStatsInterface
     */
    public function create(array $champion): ChampionStats
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
     * Create Champion Health object
     *
     * @param array $champion
     *
     * @return ChampionRegenResource
     */
    private function createHealth(array $champion) : ChampionRegenResource
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
     *
     * @return ChampionRegenResource
     */
    private function createResource(array $champion) : ChampionRegenResource
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

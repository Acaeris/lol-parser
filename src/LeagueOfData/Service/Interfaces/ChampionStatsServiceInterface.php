<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\ChampionStats;

/**
 * Champion Stats object JSON factory
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionStatsServiceInterface
{
    /**
     * Factory to create Champion Stats objects from JSON
     *
     * @param array $champion
     *
     * @return ChampionStats
     */
    public function create(array $champion) : ChampionStats;
}

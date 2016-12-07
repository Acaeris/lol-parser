<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Stats Interface
 * 
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionStats
 */
interface ChampionStatsInterface
{
    public function moveSpeed() : int;

    /**
     * Convert data to array
     *
     * @return array
     */
    public function toArray() : array;
}

<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\ChampionStats;

/**
 * Champion Stats object factory Interface
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionStatsServiceInterface
{
    /**
     * Factory to create Champion Stats objects
     *
     * @param array $champion
     *
     * @return ChampionStats
     */
    public function create(array $champion) : ChampionStats;

    /**
     * Add a champion's stats to the collection
     *
     * @param ChampionStats $champion
     */
    public function add(ChampionStats $champion);

    /**
     * Add all champion stats objects to internal array
     *
     * @param array $champions ChampionStats objects
     */
    public function addAll(array $champions);

    /**
     * Fetch Champions Stats
     *
     * @param string $version
     * @param int    $championId
     * @param string $region
     *
     * @return array ChampionStats Objects
     */
    public function fetch(string $version, int $championId = null, string $region = "euw") : array;

    /**
     * Store the champion stats in the database
     */
    public function store();

    /**
     * Get collection of champions' stats for transfer to a different process.
     *
     * @return array ChampionStats objects
     */
    public function transfer() : array;
}

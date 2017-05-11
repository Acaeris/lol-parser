<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Adapters\RequestInterface;

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
     * @return ChampionStats
     */
    public function create(array $champion) : ChampionStats;

    /**
     * Add champion stats objects to internal array
     *
     * @param array $champions ChampionStats objects
     */
    public function add(array $champions);

    /**
     * Fetch Champions Stats
     *
     * @param RequestInterface $request
     * @return array ChampionStats Objects
     */
    public function fetch(RequestInterface $request) : array;

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

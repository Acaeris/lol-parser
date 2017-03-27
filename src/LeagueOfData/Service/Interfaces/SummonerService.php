<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Summoner;

/**
 * Summoner Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface SummonerServiceInterface
{
    /**
     * Find all Summoner data
     *
     * @return array Summoner objects
     */
    public function findAll() : array;

    /**
     * Find a specific summoner
     *
     * @param int $summonerId
     *
     * @return array Summoner objects
     */
    public function find(int $summonerId) : array;

    /**
     * Store the summoner objects in the database
     */
    public function store();

    /**
     * Add a summoner to the collection
     *
     * @param Summoner $summoner
     */
    public function add(Summoner $summoner);
}

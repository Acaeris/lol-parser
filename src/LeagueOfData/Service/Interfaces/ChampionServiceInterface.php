<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\Champion;

/**
 * Champion Service interface
 *
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionServiceInterface
{

    /**
     * Add a champion to the collection
     *
     * @param Champion $champion
     */
    public function add(Champion $champion);

    /**
     * Add all champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function addAll(array $champions);

    /**
     * Fetch Champions
     *
     * @param string $version
     * @param int    $championId
     * @param string $region
     *
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null, string $region = 'euw') : array;

    /**
     * Store the champion objects in the database
     */
    public function store();

    /**
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer() : array;

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     *
     * @return Champion
     */
    public function create(array $champion) : Champion;
}

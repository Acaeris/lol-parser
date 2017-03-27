<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\Champion;

/**
 * Champion Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionServiceInterface
{
    /**
     * Find all Champion data
     *
     * @param string $version Version number
     *
     * @return array Champion objects
     */
    public function findAll(string $version) : array;

    /**
     * Find a specific champion
     *
     * @param string $version
     * @param int    $championId
     *
     * @return array Champion objects
     */
    public function find(string $version, int $championId) : array;

    /**
     * Store the champion objects in the database
     */
    public function store();

    /**
     * Add a champion to the collection
     *
     * @param Champion $champion
     */
    public function add(Champion $champion);
}

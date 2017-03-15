<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\Champion;

interface ChampionService {

    /**
     * Find all Champion data
     *
     * @param string $version Version number
     * @return array Champion objects
     */
    function findAll(string $version) : array;

    /**
     * Find a specific champion
     * 
     * @param int $championId
     * @param string $version
     * @return array Champion objects
     */
    function find(int $championId, string $version) : array;

    /**
     * Store the champion objects in the database
     */
    function store();

    /**
     * Add a champion to the collection
     * 
     * @param Champion $champion
     */
    function add(Champion $champion);
}

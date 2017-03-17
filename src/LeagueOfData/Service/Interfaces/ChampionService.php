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
     * @param string $version
     * @param int $championId
     * @return array Champion objects
     */
    function find(string $version, int $championId) : array;

    /**
     * Store the champion objects in the database
     */
    function store();

    /**
     * Fetch Champions
     * 
     * @param string $version
     * @param int $championId
     * @return array Champion Objects
     */
    function fetch(string $version, int $championId = null) : array;

    /**
     * Add a champion to the collection
     * 
     * @param Champion $champion
     */
    function add(Champion $champion);
}

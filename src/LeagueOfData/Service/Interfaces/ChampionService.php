<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\Champion;

interface ChampionService {

    /**
     * Find all Realm data
     *
     * @param string Version number
     * @return array Realm objects
     */
    function findAll(string $version) : array;
    function find($id, $version);
    function store();
    function add(Champion $champion);
}

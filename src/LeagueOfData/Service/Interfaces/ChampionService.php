<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion;

interface ChampionService {
    function findAll($version);
    function find($id, $version);
    function store();
    function add(Champion $champion);
}

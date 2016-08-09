<?php

namespace LeagueOfData\Models;

use LeagueOfData\Adapters\AdapterInterface;

interface ChampionInterface {
    public function name();
    public function toArray();
    public function store(AdapterInterface $adapter);
}

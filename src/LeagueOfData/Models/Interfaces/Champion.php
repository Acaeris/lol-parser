<?php

namespace LeagueOfData\Models\Interfaces;

use LeagueOfData\Adapters\AdapterInterface;

interface Champion {
    public function name();
    public function toArray();
    public function store(AdapterInterface $adapter);
}

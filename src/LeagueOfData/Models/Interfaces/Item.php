<?php

namespace LeagueOfData\Models\Interfaces;

use LeagueOfData\Adapters\AdapterInterface;

interface Item {
    public function name();
    public function toArray();
    public function store(AdapterInterface $adapter);
}

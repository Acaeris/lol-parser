<?php

namespace LeagueOfData\Models\Interfaces;

use LeagueOfData\Adapters\AdapterInterface;

interface Version {
    public function toArray();
    public function versionNumber();
    public function store(AdapterInterface $adapter);
}

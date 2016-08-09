<?php

namespace LeagueOfData\Models;

use LeagueOfData\Adapters\AdapterInterface;

interface VersionInterface {
    public function toArray();
    public function versionNumber();
    public function store(AdapterInterface $adapter);
}

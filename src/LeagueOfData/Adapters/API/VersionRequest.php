<?php

namespace LeagueOfData\Adapters\API;

use LeagueOfData\Adapters\API\Request;

final class VersionRequest implements Request
{
    private $call = 'https://global.api.pvp.net/api/lol/static-data/euw/v1.2/versions';

    public function __construct($params) {
    }

    public function call() {
        return $this->call;
    }

    public function params() {
        return [];
    }
}

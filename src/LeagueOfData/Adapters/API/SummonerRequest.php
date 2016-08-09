<?php

namespace LeagueOfData\Adapters\API;

use LeagueOfData\Adapters\API\Request;

final class SummonerRequest implements Request {

    private $baseurl = 'https://euw.api.pvp.net/api/lol/{region}/v1.4/summoner/';
    private $call;

    public function __construct() {
    }

    public function prepare($params) {
        $this->validate($params);
        $url = $this->baseurl . (isset($params['name']) ? 'by-name/' . $params['name'] : $params['ids']);
        $this->call = str_replace('{region}', $params['region'], $url);
    }

    private function validate($params) {
        if (!isset($params['region'])) {
            throw new \Exception('Region not specified for Summoner API request');
        }
        if (!isset($params['ids']) && !isset($params['name'])) {
            throw new \Exception('No ID or Name supplied for Summoner API request');
        }
    }

    public function call() {
        return $this->call;
    }
}

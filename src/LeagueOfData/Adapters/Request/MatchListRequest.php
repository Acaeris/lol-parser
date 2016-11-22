<?php

namespace LeagueOfData\Adapters\API;

use LeagueOfData\Adapters\API\Request;

final class MatchListRequest implements Request {

    private $baseurl = 'https://euw.api.pvp.net/api/lol/{region}/v2.2/matchlist/by-summoner/';
    private $call;

    public function __construct() {
    }

    public function prepare($params) {
        $this->validate($params);
        $url = $this->baseurl . $params['id'];
        $this->call = str_replace('{region}', $params['region'], $url);
    }

    private function validate($params) {
        if (!isset($params['region'])) {
            throw new \Exception('Region not specified for Match History API request');
        }
        if (!isset($params['id'])) {
            throw new \Exception('No ID supplied for Match History API request');
        }
    }

    public function call() {
        return $this->call;
    }

}

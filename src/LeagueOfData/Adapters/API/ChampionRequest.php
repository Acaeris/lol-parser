<?php

namespace LeagueOfData\Adapters\API;

use LeagueOfData\Adapters\API\Request;

final class ChampionRequest implements Request {

    private $baseurl = 'https://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion';
    private $call;
    private $params = [ 'champData' => 'all' ];

    public function __construct($params) {
        $this->validate($params);
        $this->call = $this->baseurl . (isset($params['id']) ? $params['id'] : '');
        if (isset($params['version'])) {
            $this->params['version'] = $params['version'];
        }
    }

    private function validate($params) {
        if (isset($params['id']) && !is_int($params['id'])) {
            throw new \Exception("Invalid ID supplied for Champion request");
        }
        // TODO: Add version validation
    }

    public function call() {
        return $this->call;
    }

    public function params() {
        return $this->params;
    }
}

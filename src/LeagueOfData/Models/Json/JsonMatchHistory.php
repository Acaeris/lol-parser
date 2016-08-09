<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\MatchHistory;

class JsonMatchHistory implements MatchHistory
{
    private $data;

    public function __construct($json)
    {
        $this->data = $json;
    }

    public function id()
    {
        return $this->data->matchId;
    }

    public function region()
    {
        return $this->data->region;
    }
}

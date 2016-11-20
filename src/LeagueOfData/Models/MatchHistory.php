<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\MatchHistory as MatchHistoryInterface;

class MatchHistory implements MatchHistoryInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
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

<?php

namespace LeagueOfData\Models;

final class MatchHistory
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getID()
    {
        return $this->data->matchId;
    }

    public function region()
    {
        return $this->data->region;
    }
}

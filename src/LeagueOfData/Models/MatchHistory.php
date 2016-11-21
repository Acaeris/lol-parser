<?php

namespace LeagueOfData\Models;

final class MatchHistory
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

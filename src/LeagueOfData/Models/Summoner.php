<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\Summoner as SummonerInterface;

final class Summoner implements SummonerInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function icon() {
        return $this->data->profileIconId;
    }

    public function id() {
        return $this->data->id;
    }

    public function level() {
        return $this->data->summonerLevel;
    }

    public function name() {
        return $this->data->name;
    }

    public function revisionDate() {
        return $this->data->revisionDate;
    }

}

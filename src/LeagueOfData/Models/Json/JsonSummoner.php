<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\Interfaces\Summoner;

final class JsonSummoner implements Summoner
{
    private $data;

    public function __construct($json)
    {
        $this->data = $json;
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

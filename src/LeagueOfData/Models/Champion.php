<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\ChampionStats;

final class Champion {

    private $id;
    private $name;
    private $title;
    private $version;
    private $stats;

    public function __construct($id, $name, $title, ChampionStats $stats, $version)
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->version = $version;
        $this->stats = $stats;
    }

    public function id() {
        return $this->id;
    }

    public function version() {
        return $this->version;
    }

    public function toArray() {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'version' => $this->version
        ], $this->stats->toArray());
    }
}

<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\Champion as ChampionInterface;
use LeagueOfData\Models\Interfaces\Stats;

final class Champion implements ChampionInterface {

    private $id;
    private $name;
    private $title;
    private $version;
    private $stats;

    public function __construct($id, $name, $title, $version, Stats $stats)
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->version = $version;
        $this->stats = $stats;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'stats' => $this->stats,
            'version' => $this->version
        ];
    }
}

<?php

namespace LeagueOfData\Helpers\Format;

class MapName
{
    private $maps = [
        11 => "Summoner's Rift"
    ];

    public function format($mapId)
    {
        if (isset($this->maps[$mapId])) {
            return $this->maps[$mapId];
        }
        return "Unknown Map ID: ".$mapId;
    }
}

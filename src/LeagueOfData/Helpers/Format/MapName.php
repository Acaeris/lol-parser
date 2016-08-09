<?php

namespace LeagueOfData\Helpers\Format;

class MapName
{
    private $_maps = [
        11 => "Summoner's Rift"
    ];

    public function format($mapId)
    {
        if (isset($this->_maps[$mapId])) {
            return $this->_maps[$mapId];
        }
        return "Unknown Map ID: " . $mapId;
    }
}

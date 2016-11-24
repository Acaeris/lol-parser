<?php

namespace LeagueOfData\Models;

final class Spell
{
    private $ranges;
    private $resource;
    private $maxRank;
    private $cooldowns;
    private $costs;

    public function __construct($ranges, $resource, $maxRank, $cooldowns, $costs)
    {
        $this->ranges = $ranges;
        $this->resource = $resource;
        $this->maxRank = $maxRank;
        $this->cooldowns = $cooldowns;
        $this->costs = $costs;
    }

    public function toArray()
    {
        
    }
}

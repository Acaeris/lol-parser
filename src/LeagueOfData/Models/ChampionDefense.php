<?php

namespace LeagueOfData\Models;

final class ChampionDefense
{
    const DEFENSE_ARMOR = 'armor';
    const DEFENSE_MAGICRESIST = 'spellBlock';

    private $type;
    private $baseValue;
    private $perLevel;

    public function __construct($type, $base, $perLevel)
    {
        $this->type = $type;
        $this->baseValue = $base;
        $this->perLevel = $perLevel;
    }

    public function toArray()
    {
        return [
            $this->type => $this->baseValue,
            $this->type . 'PerLevel' => $this->perLevel
        ];
    }
}

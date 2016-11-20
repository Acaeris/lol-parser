<?php

namespace LeagueOfData\Models;

final class ChampionHealth
{
    private $baseHp;
    private $hpPerLevel;
    private $regen;
    private $regenPerLevel;

    public function __construct($baseHp, $hpPerLevel, $regen, $regenPerLevel)
    {
        $this->baseHp = $baseHp;
        $this->hpPerLevel = $hpPerLevel;
        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    public function toArray()
    {
        return [
            'hp' => $this->baseHp,
            'hpPerLevel' => $this->hpPerLevel,
            'hpRegen' => $this->regen,
            'hpRegenPerLevel' => $this->regenPerLevel
        ];
    }
}

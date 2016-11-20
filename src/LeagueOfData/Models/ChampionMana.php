<?php

namespace LeagueOfData\Models;

final class ChampionMana
{
    private $baseMp;
    private $mpPerLevel;
    private $regen;
    private $regenPerLevel;

    public function __construct($baseMp, $mpPerLevel, $regen, $regenPerLevel)
    {
        $this->baseMp = $baseMp;
        $this->mpPerLevel = $mpPerLevel;
        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    public function toArray()
    {
        return [
            'mp' => $this->baseMp,
            'mpPerLevel' => $this->mpPerLevel,
            'mpRegen' => $this->regen,
            'mpRegenPerLevel' => $this->regenPerLevel
        ];
    }
}
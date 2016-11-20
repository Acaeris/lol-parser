<?php

namespace LeagueOfData\Models;

final class ChampionAttack
{
    private $range;
    private $damage;
    private $damagePerLevel;
    private $speedOffset;
    private $speedPerLevel;
    private $crit;
    private $critPerLevel;

    public function __construct($range, $damage, $damagePerLevel, $speedOffset,
        $speedPerLevel, $crit, $critPerLevel)
    {
        $this->range = $range;
        $this->damage = $damage;
        $this->damagePerLevel = $damagePerLevel;
        $this->speedOffset = $speedOffset;
        $this->speedPerLevel = $speedPerLevel;
        $this->crit = $crit;
        $this->critPerLevel = $critPerLevel;
    }

    public function toArray()
    {
        return [
            'attackRange' => $this->range,
            'attackDamage' => $this->damage,
            'attackDamagePerLevel' => $this->damagePerLevel,
            'attackSpeedOffset' => $this->speedOffset,
            'attackSpeedPerLevel' => $this->speedPerLevel,
            'crit' => $this->crit,
            'critPerLevel' => $this->critPerLevel
        ];
    }
}
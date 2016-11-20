<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\Stats as StatsInterface;
use LeagueOfData\Models\ChampionHealth;
use LeagueOfData\Models\ChampionMana;

final class ChampionStats implements StatsInterface
{
    private $health;
    private $mana;
    private $attack;
    private $armor;
    private $armorPerLevel;
    private $spellBlock;
    private $spellBlockPerLevel;
    private $moveSpeed;

    public function __construct(
        ChampionHealth $health,
        ChampionMana $mana,
        ChampionAttack $attack
    ) {
        $this->health = $health;
        $this->mana = $mana;
        $this->attack = $attack;
    }
    
    public function toArray()
    {
        return [];
    }
}
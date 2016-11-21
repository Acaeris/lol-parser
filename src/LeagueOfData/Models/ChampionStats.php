<?php

namespace LeagueOfData\Models;

final class ChampionStats
{
    private $health;
    private $resource;
    private $attack;
    private $armor;
    private $magicResist;
    private $moveSpeed;

    public function __construct(
        ChampionResource $health,
        ChampionResource $mana,
        ChampionAttack $attack,
        ChampionDefense $armor,
        ChampionDefense $magicResist,
        $moveSpeed
    ) {
        $this->health = $health;
        $this->resource = $mana;
        $this->attack = $attack;
        $this->armor = $armor;
        $this->magicResist = $magicResist;
        $this->moveSpeed = $moveSpeed;
    }
    
    public function toArray()
    {
        return array_merge(['moveSpeed' => $this->moveSpeed], $this->health->toArray(), $this->resource->toArray(),
            $this->attack->toArray(), $this->armor->toArray(), $this->magicResist->toArray());
    }
}
<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

final class ChampionStats implements ChampionStatsInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var ChamptionResource Health details
     */
    private $health;

    /**
     * @var ChampionResource Resource details (e.g. Mana, Rage, Energy)
     */
    private $resource;

    /**
     * @var ChampionAttack Attack details 
     */
    private $attack;

    /**
     * @var ChampionDefense Armor details 
     */
    private $armor;

    /**
     * @var ChampionDefense Magic resistance details
     */
    private $magicResist;

    /**
     * @var int Movement Speed
     */
    private $moveSpeed;

    /**
     * Factory Constructor
     * 
     * @param array $champion
     * @return \LeagueOfData\Models\ChampionStats
     */
    public static function fromState(array $champion) : ChampionStats
    {
        $health = ChampionResource::fromState(ChampionResource::RESOURCE_HEALTH, $champion);
        $resource = ChampionResource::fromState(ChampionResource::RESOURCE_MANA, $champion);
        $attack = ChampionAttack::fromState($champion);
        $armor = ChampionDefense::fromState(ChampionDefense::DEFENSE_ARMOR, $champion);
        $magicResist = ChampionDefense::fromState(ChampionDefense::DEFENSE_MAGICRESIST, $champion);
        
        return new self($health, $resource, $attack, $armor, $magicResist, $champion['moveSpeed']);
    }

    /**
     * Main Constructor
     * 
     * @param \LeagueOfData\Models\ChampionResource $health
     * @param \LeagueOfData\Models\ChampionResource $mana
     * @param \LeagueOfData\Models\ChampionAttack $attack
     * @param \LeagueOfData\Models\ChampionDefense $armor
     * @param \LeagueOfData\Models\ChampionDefense $magicResist
     * @param int $moveSpeed
     */
    public function __construct(
        ChampionResource $health,
        ChampionResource $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist,
        int $moveSpeed
    ) {
        $this->constructImmutable();

        $this->health = $health;
        $this->resource = $mana;
        $this->attack = $attack;
        $this->armor = $armor;
        $this->magicResist = $magicResist;
        $this->moveSpeed = $moveSpeed;
    }

    public function moveSpeed() : int
    {
        return $this->moveSpeed;
    }

    /**
     * Convert data to array
     *
     * @return array
     */
    public function toArray() : array
    {
        return array_merge(['moveSpeed' => $this->moveSpeed], $this->health->toArray(), $this->resource->toArray(),
            $this->attack->toArray(), $this->armor->toArray(), $this->magicResist->toArray());
    }
}
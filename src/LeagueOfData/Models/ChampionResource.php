<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class ChampionResource implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    const RESOURCE_HEALTH = 'hp';
    const RESOURCE_MANA = 'mp';

    private $type;
    private $baseValue;
    private $perLevel;
    private $regen;
    private $regenPerLevel;

    public static function fromState(string $type, array $champion) : ChampionResource
    {
        return new self(
            $type,
            $champion[$type],
            $champion[$type . 'PerLevel'],
            $champion[$type . 'Regen'],
            $champion[$type . 'RegenPerLevel']
        );
    }

    public function __construct(string $type, int $baseValue, int $perLevel, int $regen, int $regenPerLevel)
    {
        $this->constructImmutable();

        $this->type = $type;
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    public function toArray() : array
    {
        return [
            $this->type => $this->baseValue,
            $this->type . 'PerLevel' => $this->perLevel,
            $this->type . 'Regen' => $this->regen,
            $this->type . 'RegenPerLevel' => $this->regenPerLevel
        ];
    }

    public function type() : string
    {
        return $this->type;
    }

    public function baseValue() : int
    {
        return $this->baseValue;
    }

    public function increasePerLevel() : int
    {
        return $this->perLevel;
    }

    public function valueAtLevel(int $level) : int
    {
        return $this->baseValue + $this->perLevel * $level;
    }

    public function regenBaseValue() : int
    {
        return $this->regen;
    }

    public function regenIncreasePerLevel() : int
    {
        return $this->regenPerLevel;
    }

    public function regenAtLevel(int $level) : int
    {
        return $this->regen + $this->regenPerLevel * $level;
    }
}
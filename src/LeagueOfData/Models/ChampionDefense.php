<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class ChampionDefense implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    const DEFENSE_ARMOR = 'armor';
    const DEFENSE_MAGICRESIST = 'spellBlock';

    private $type;
    private $baseValue;
    private $perLevel;

    public static function fromState(string $type, array $champion) : ChampionDefense
    {
        return new self(
            $type,
            $champion[$type],
            $champion[$type . 'PerLevel']
        );
    }

    public function __construct(string $type, int $base, int $perLevel)
    {
        $this->constructImmutable();

        $this->type = $type;
        $this->baseValue = $base;
        $this->perLevel = $perLevel;
    }

    public function toArray() : array
    {
        return [
            $this->type => $this->baseValue,
            $this->type . 'PerLevel' => $this->perLevel
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
}

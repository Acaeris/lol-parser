<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class ChampionAttack implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    private $range;
    private $damage;
    private $damagePerLevel;
    private $speedOffset;
    private $speedPerLevel;
    private $crit;
    private $critPerLevel;

    public static function fromState(array $champion) : ChampionAttack
    {
        return new self(
            $champion['attackRange'],
            $champion['attackDamage'],
            $champion['attackDamagePerLevel'],
            $champion['attackSpeedOffset'],
            $champion['attackSpeedPerLevel'],
            $champion['crit'],
            $champion['critPerLevel']
        );
    }

    public function __construct(
        int $range,
        int $damage,
        int $damagePerLevel,
        int $speedOffset,
        int $speedPerLevel,
        int $crit,
        int $critPerLevel
    ) {
        $this->constructImmutable();

        $this->range = $range;
        $this->damage = $damage;
        $this->damagePerLevel = $damagePerLevel;
        $this->speedOffset = $speedOffset;
        $this->speedPerLevel = $speedPerLevel;
        $this->crit = $crit;
        $this->critPerLevel = $critPerLevel;
    }

    public function toArray() : array
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

    public function range() : int
    {
        return $this->range;
    }

    public function baseDamage() : int
    {
        return $this->damage;
    }

    public function damagePerLevel() : int
    {
        return $this->damagePerLevel;
    }

    public function damageAtLevel(int $level) : int
    {
        return $this->damage + $this->damagePerLevel * $level;
    }

    public function attackSpeed() : int
    {
        return $this->speedOffset;
    }

    public function attackSpeedPerLevel() : int
    {
        return $this->speedPerLevel;
    }

    public function attackSpeedAtLevel(int $level) : int
    {
        return $this->speedOffset + $this->speedPerLevel * $level;
    }

    public function baseCritChance() : int
    {
        return $this->crit;
    }

    public function critChancePerLevel() : int
    {
        return $this->critPerLevel;
    }

    public function critChanceAtLevel(int $level) : int
    {
        return $this->crit + $this->critPerLevel * $level;
    }
}
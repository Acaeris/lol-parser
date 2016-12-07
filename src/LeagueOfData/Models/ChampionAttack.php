<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

/**
 * Champion Attack.
 * Used to represent the basic attack of the champion
 * 
 * @author caitlyn.osborne
 */
final class ChampionAttack implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /** @var int Attack Range */
    private $range;
    /** @var int Base Attack Damage */
    private $damage;
    /** @var int Base Attack Damage increase per level */
    private $damagePerLevel;
    /** @var int Attack speed base */
    private $speedOffset;
    /** @var int Attack speed increase per level */
    private $speedPerLevel;
    /** @var int Base critical hit chance */
    private $crit;
    /** @var int Critical hit chance increase per level */
    private $critPerLevel;

    /**
     * Creates a new Champion Attack from an existing state.
     * Use as an alternative constructor as PHP does not support multiple constructors.
     * 
     * @param array $champion Data from an existing state (e.g. SQL result, Json, or object converted to array)
     * @return \LeagueOfData\Models\ChampionAttack Resultant Champion Attack
     */
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

    /**
     * Construct a Champion Attack object
     * 
     * @param int $range Attack range
     * @param int $damage Base attack damage
     * @param int $damagePerLevel Attack damage increase per level
     * @param int $speedOffset Base Attack speed
     * @param int $speedPerLevel Attack speed increase per level
     * @param int $crit Base critical hit chance
     * @param int $critPerLevel Critical hit chance increase per level
     */
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

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Champion attack data as an array
     */
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

    /**
     * Attack range
     * 
     * @return int Attack range
     */
    public function range() : int
    {
        return $this->range;
    }

    /**
     * Base attack damage
     * 
     * @return int Base attack damage
     */
    public function baseDamage() : int
    {
        return $this->damage;
    }

    /**
     * Attack damage increase per level
     * 
     * @return int Attack damage increase per level
     */
    public function damagePerLevel() : int
    {
        return $this->damagePerLevel;
    }

    /**
     * Calculate attack damage at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of attack damage at the given level
     */
    public function damageAtLevel(int $level) : int
    {
        return $this->damage + $this->damagePerLevel * $level;
    }

    /**
     * Base attack speed
     * 
     * @return int Base attack speed
     */
    public function attackSpeed() : int
    {
        return $this->speedOffset;
    }

    /**
     * Attack speed increase per level
     * 
     * @return int Attack speed increase per level
     */
    public function attackSpeedPerLevel() : int
    {
        return $this->speedPerLevel;
    }

    /**
     * Calculate the attack speed at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of the attack speed at the given level
     */
    public function attackSpeedAtLevel(int $level) : int
    {
        return $this->speedOffset + $this->speedPerLevel * $level;
    }

    /**
     * Base critical hit chance
     * 
     * @return int Base critical hit chance
     */
    public function baseCritChance() : int
    {
        return $this->crit;
    }

    /**
     * Critical hit chance increase per level
     * 
     * @return int Critical hit chance per level
     */
    public function critChancePerLevel() : int
    {
        return $this->critPerLevel;
    }

    /**
     * Calculate the critical hit chance at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of the critical hit chance at the given level
     */
    public function critChanceAtLevel(int $level) : int
    {
        return $this->crit + $this->critPerLevel * $level;
    }
}
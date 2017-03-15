<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;

/**
 * Champion Attack.
 * Used to represent the basic attack of the champion
 *
 * @author caitlyn.osborne
 */
final class ChampionAttack implements ChampionAttackInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /** @var float Attack Range */
    private $range;
    /** @var float Base Attack Damage */
    private $damage;
    /** @var float Base Attack Damage increase per level */
    private $damagePerLevel;
    /** @var float Attack speed base */
    private $speedOffset;
    /** @var float Attack speed increase per level */
    private $speedPerLevel;
    /** @var float Base critical hit chance */
    private $crit;
    /** @var float Critical hit chance increase per level */
    private $critPerLevel;

    /**
     * Creates a new Champion Attack from an existing state.
     * Use as an alternative constructor as PHP does not support multiple constructors.
     *
     * @param array $champion Data from an existing state (e.g. SQL result, Json, or object converted to array)
     * @return ChampionAttackInterface Resultant Champion Attack
     */
    public static function fromState(array $champion) : ChampionAttackInterface
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
     * @param float $range Attack range
     * @param float $damage Base attack damage
     * @param float $damagePerLevel Attack damage increase per level
     * @param float $speedOffset Base Attack speed
     * @param float $speedPerLevel Attack speed increase per level
     * @param float $crit Base critical hit chance
     * @param float $critPerLevel Critical hit chance increase per level
     */
    public function __construct(
        float $range,
        float $damage,
        float $damagePerLevel,
        float $speedOffset,
        float $speedPerLevel,
        float $crit,
        float $critPerLevel
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
     * @return float Attack range
     */
    public function range() : float
    {
        return round($this->range, 2);
    }

    /**
     * Base attack damage
     *
     * @return float Base attack damage
     */
    public function baseDamage() : float
    {
        return round($this->damage, 3);
    }

    /**
     * Attack damage increase per level
     *
     * @return float Attack damage increase per level
     */
    public function damagePerLevel() : float
    {
        return round($this->damagePerLevel, 3);
    }

    /**
     * Calculate attack damage at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of attack damage at the given level
     */
    public function damageAtLevel(int $level) : float
    {
        return round($this->damage + $this->damagePerLevel * ($level - 1), 3);
    }

    /**
     * Base attack speed
     *
     * @return float Base attack speed
     */
    public function attackSpeed() : float
    {
        return round($this->speedOffset, 2);
    }

    /**
     * Attack speed increase per level
     *
     * @return float Attack speed increase per level
     */
    public function attackSpeedPerLevel() : float
    {
        return round($this->speedPerLevel, 2);
    }

    /**
     * Calculate the attack speed at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the attack speed at the given level
     */
    public function attackSpeedAtLevel(int $level) : float
    {
        return round($this->speedOffset + $this->speedPerLevel * ($level - 1), 2);
    }

    /**
     * Base critical hit chance
     *
     * @return float Base critical hit chance
     */
    public function baseCritChance() : float
    {
        return round($this->crit, 2);
    }

    /**
     * Critical hit chance increase per level
     *
     * @return float Critical hit chance per level
     */
    public function critChancePerLevel() : float
    {
        return round($this->critPerLevel, 2);
    }

    /**
     * Calculate the critical hit chance at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the critical hit chance at the given level
     */
    public function critChanceAtLevel(int $level) : float
    {
        return round($this->crit + $this->critPerLevel * ($level - 1), 2);
    }
}
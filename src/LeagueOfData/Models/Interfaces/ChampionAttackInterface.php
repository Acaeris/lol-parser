<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Attack Interface
 * 
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionAttack
 */
interface ChampionAttackInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Champion attack data as an array
     */
    public function toArray() : array;

    /**
     * Attack range
     * 
     * @return int Attack range
     */
    public function range() : int;

    /**
     * Base attack damage
     * 
     * @return int Base attack damage
     */
    public function baseDamage() : int;

    /**
     * Attack damage increase per level
     * 
     * @return int Attack damage increase per level
     */
    public function damagePerLevel() : int;

    /**
     * Calculate attack damage at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of attack damage at the given level
     */
    public function damageAtLevel(int $level) : int;

    /**
     * Base attack speed
     * 
     * @return int Base attack speed
     */
    public function attackSpeed() : int;

    /**
     * Attack speed increase per level
     * 
     * @return int Attack speed increase per level
     */
    public function attackSpeedPerLevel() : int;

    /**
     * Calculate the attack speed at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of the attack speed at the given level
     */
    public function attackSpeedAtLevel(int $level) : int;

    /**
     * Base critical hit chance
     * 
     * @return int Base critical hit chance
     */
    public function baseCritChance() : int;

    /**
     * Critical hit chance increase per level
     * 
     * @return int Critical hit chance per level
     */
    public function critChancePerLevel() : int;

    /**
     * Calculate the critical hit chance at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of the critical hit chance at the given level
     */
    public function critChanceAtLevel(int $level) : int;
}

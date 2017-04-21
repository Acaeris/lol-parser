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
     * Attack range
     *
     * @return float Attack range
     */
    public function range() : float;

    /**
     * Base attack damage
     *
     * @return float Base attack damage
     */
    public function baseDamage() : float;

    /**
     * Attack damage increase per level
     *
     * @return float Attack damage increase per level
     */
    public function damagePerLevel() : float;

    /**
     * Calculate attack damage at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of attack damage at the given level
     */
    public function damageAtLevel(int $level) : float;

    /**
     * Base attack speed
     *
     * @return float Base attack speed
     */
    public function attackSpeed() : float;

    /**
     * Attack speed increase per level
     *
     * @return float Attack speed increase per level
     */
    public function attackSpeedPerLevel() : float;

    /**
     * Calculate the attack speed at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the attack speed at the given level
     */
    public function attackSpeedAtLevel(int $level) : float;

    /**
     * Base critical hit chance
     *
     * @return float Base critical hit chance
     */
    public function baseCritChance() : float;

    /**
     * Critical hit chance increase per level
     *
     * @return float Critical hit chance per level
     */
    public function critChancePerLevel() : float;

    /**
     * Calculate the critical hit chance at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the critical hit chance at the given level
     */
    public function critChanceAtLevel(int $level) : float;
}

<?php

namespace App\Models\Champion;

/**
 * Champion Attack Interface
 *
 * @author caitlyn.osborne
 */
interface ChampionAttackInterface
{
    /**
     * Attack range
     *
     * @return float Attack range
     */
    public function getRange() : float;

    /**
     * Base attack damage
     *
     * @return float Base attack damage
     */
    public function getBaseDamage() : float;

    /**
     * Attack damage increase per level
     *
     * @return float Attack damage increase per level
     */
    public function getDamagePerLevel() : float;

    /**
     * Calculate attack damage at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of attack damage at the given level
     */
    public function calculateDamageAtLevel(int $level) : float;

    /**
     * Base attack speed
     *
     * @return float Base attack speed
     */
    public function getAttackSpeed() : float;

    /**
     * Attack speed increase per level
     *
     * @return float Attack speed increase per level
     */
    public function getAttackSpeedPerLevel() : float;

    /**
     * Calculate the attack speed at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the attack speed at the given level
     */
    public function calculateAttackSpeedAtLevel(int $level) : float;

    /**
     * Base critical hit chance
     *
     * @return float Base critical hit chance
     */
    public function getBaseCritChance() : float;

    /**
     * Critical hit chance increase per level
     *
     * @return float Critical hit chance per level
     */
    public function getCritChancePerLevel() : float;

    /**
     * Calculate the critical hit chance at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of the critical hit chance at the given level
     */
    public function calculateCritChanceAtLevel(int $level) : float;
}

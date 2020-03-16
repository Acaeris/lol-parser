<?php

namespace App\Models\Champion;

/**
 * Champion Resource Interface
 *
 * @author caitlyn.osborne
 */
interface ChampionRegenResourceInterface
{
    /**
     * Base regeneration rate
     *
     * @return float Base regeneration rate
     */
    public function getRegenBaseValue() : float;

    /**
     * Regeneration rate increase per level
     *
     * @return float Regeneration rate increase per level
     */
    public function getRegenIncreasePerLevel() : float;

    /**
     * Calculate the regeneration rate at given level
     *
     * @param int $level Level of the champion
     * @return float Value of regeneration rate at the given level
     */
    public function calculateRegenAtLevel(int $level) : float;
}

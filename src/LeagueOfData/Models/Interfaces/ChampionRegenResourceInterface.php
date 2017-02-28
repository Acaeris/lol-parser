<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Resource Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Champion\ChampionRegenResource
 */
interface ChampionRegenResourceInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion resource data as an array
     */
    public function toArray() : array;

    /**
     * Base regeneration rate
     *
     * @return float Base regeneration rate
     */
    public function regenBaseValue() : float;

    /**
     * Regeneration rate increase per level
     *
     * @return float Regeneration rate increase per level
     */
    public function regenIncreasePerLevel() : float;

    /**
     * Calculate the regeneration rate at given level
     *
     * @param int $level Level of the champion
     * @return float Value of regeneration rate at the given level
     */
    public function regenAtLevel(int $level) : float;
}

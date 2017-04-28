<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * General Resource Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfLegends\Models\ResourceTrait
 */
interface ResourceInterface
{
    /**
     * Base resource value
     *
     * @return float Base resource value
     */
    public function getBaseValue() : float;

    /**
     * Base resource increase per level
     *
     * @return float Base resource increase per level
     */
    public function getIncreasePerLevel() : float;

    /**
     * Calculate the max resource value at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of max resource at the given level
     */
    public function calculateValueAtLevel(int $level) : float;
}

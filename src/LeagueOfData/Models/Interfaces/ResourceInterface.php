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
     * Resource Type
     *
     * @return string Resource type
     */
    public function type() : string;

    /**
     * Base resource value
     *
     * @return float Base resource value
     */
    public function baseValue() : float;

    /**
     * Base resource increase per level
     *
     * @return float Base resource increase per level
     */
    public function increasePerLevel() : float;

    /**
     * Calculate the max resource value at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of max resource at the given level
     */
    public function valueAtLevel(int $level) : float;
}

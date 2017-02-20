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
     * @return int Base resource value
     */
    public function baseValue() : int;

    /**
     * Base resource increase per level
     * 
     * @return int Base resource increase per level
     */
    public function increasePerLevel() : int;

    /**
     * Calculate the max resource value at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of max resource at the given level
     */
    public function valueAtLevel(int $level) : int;
}

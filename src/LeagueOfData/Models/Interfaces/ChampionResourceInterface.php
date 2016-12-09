<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Resource Interface
 * 
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionResource
 */
interface ChampionResourceInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Champion resource data as an array
     */
    public function toArray() : array;

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

    /**
     * Base regeneration rate
     * 
     * @return int Base regeneration rate
     */
    public function regenBaseValue() : int;

    /**
     * Regeneration rate increase per level
     * 
     * @return int Regeneration rate increase per level
     */
    public function regenIncreasePerLevel() : int;

    /**
     * Calculate the regeneration rate at given level
     * 
     * @param int $level Level of the champion
     * @return int Value of regeneration rate at the given level
     */
    public function regenAtLevel(int $level) : int;
}

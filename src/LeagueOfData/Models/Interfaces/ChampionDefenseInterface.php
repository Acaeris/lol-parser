<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Defense Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionDefense
 */
interface ChampionDefenseInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion defence data as an array
     */
    public function toArray() : array;

    /**
     * Defence type
     *
     * @return string Defence type
     */
    public function type() : string;

    /**
     * Base defence value
     *
     * @return float Base defence value
     */
    public function baseValue() : float;

    /**
     * Base defence increase per level
     *
     * @return float Base defence increase per level
     */
    public function increasePerLevel() : float;

    /**
     * Calculate the amount of defence at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of defence at the given level
     */
    public function valueAtLevel(int $level) : float;
}

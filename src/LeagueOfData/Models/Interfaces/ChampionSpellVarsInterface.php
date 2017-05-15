<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Spell variables
 * Used for templating variables in spell tooltips
 *
 * @author caitlyn.osborne
 */
interface ChampionSpellVarsInterface
{
    /**
     * Get the variable stat link
     */
    public function getLink() : string;

    /**
     * Get the variable coeff array
     */
    public function getCoeff() : array;

    /**
     * Get the variable key
     */
    public function getKey() : string;

    /**
     * Get the coeff value by rank
     *
     * @param int $rank
     */
    public function getCoeffByRank(int $rank) : float;
}

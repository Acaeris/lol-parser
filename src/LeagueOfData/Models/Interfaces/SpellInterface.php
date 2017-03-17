<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Spell Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Spell
 */
interface SpellInterface
{
    /**
     * Array of Spell data
     *
     * @return array
     */
    public function toArray() : array;

    /**
     * Spell ID
     *
     * @return int
     */
    public function getID() : int;

    /**
     * Spell Name
     *
     * @return string
     */
    public function name() : string;

    /**
     * Spell description
     *
     * @return string
     */
    public function description() : string;

    /**
     * Max rank of the spell
     *
     * @return int Max Rank
     */
    public function maxRank() : int;

    /**
     * Spell Cooldowns
     *
     * @return array
     */
    public function cooldowns() : array;

    /**
     * Spell Cooldown by Rank
     *
     * @param int $rank
     * 
     * @return int
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function cooldownByRank(int $rank) : int;

    /**
     * Spells costs
     *
     * @return array
     */
    public function costs() : array;

    /**
     * Spell cost by rank
     *
     * @param int $rank
     * 
     * @return int
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function costByRank(int $rank) : int;
}

<?php

namespace LeagueOfData\Entity\Interfaces;

/**
 * Spell Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Spell
 */
interface SpellInterface
{
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
     * Default spell key binding
     *
     * @return string
     */
    public function keyBinding() : string;

    /**
     * Spell icon file name
     *
     * @return string
     */
    public function fileName() : string;

    /**
     * Spell description
     *
     * @return string
     */
    public function description() : string;

    /**
     * Tooltip text
     *
     * @return string
     */
    public function tooltip() : string;

    /**
     * Resource Type
     *
     * @return string
     */
    public function resourceType() : string;

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
     * Spells Ranges
     *
     * @return array
     */
    public function ranges() : array;

    /**
     * Range by Rank
     *
     * @param int $rank
     *
     * @return int
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function rangeByRank(int $rank) : int;

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

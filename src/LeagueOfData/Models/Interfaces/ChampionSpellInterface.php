<?php

namespace LeagueOfData\Models\Interfaces;

use LeagueOfData\Models\Interfaces\ChampionSpellResourceInterface;

/**
 * Champion Spell Interface
 *
 * @author caitlyn.osborne
 */
interface ChampionSpellInterface
{
    /**
     * Get Champion ID
     */
    public function getChampionID() : int;

    /**
     * Get Spell Name
     */
    public function getSpellName() : string;

    /**
     * Get Spell control key
     */
    public function getKey() : string;

    /**
     * Get icon image filename
     */
    public function getImageName() : string;

    /**
     * Get max spell rank
     */
    public function getMaxRank() : int;

    /**
     * Get spell description
     */
    public function getDescription() : string;

    /**
     * Get spell tooltip
     */
    public function getTooltip() : string;

    /**
     * Get spell cooldowns
     */
    public function getCooldowns() : array;

    /**
     * Get spell cooldown by rank
     *
     * @param int $rank
     */
    public function getCooldownByRank(int $rank) : int;

    /**
     * Get spell ranges
     */
    public function getRanges() : array;

    /**
     * Get spell range by rank
     *
     * @param int $rank
     */
    public function getRangeByRank(int $rank) : int;

    /**
     * Get spell effects
     */
    public function getEffects() : array;

    /**
     * Get spell effect by key
     *
     * @param int $key
     */
    public function getEffectByKey(int $key) : array;

    /**
     * Get spell effect value by key and rank
     *
     * @param int $key
     * @param int $rank
     */
    public function getEffectValue(int $key, int $rank) : float;

    /**
     * Get spell variables
     */
    public function getVars() : array;

    /**
     * Get spell resource
     */
    public function getResource() : ChampionSpellResourceInterface;

    /**
     * Get spell version
     */
    public function getVersion() : string;

    /**
     * Get spell region
     */
    public function getRegion() : string;
}

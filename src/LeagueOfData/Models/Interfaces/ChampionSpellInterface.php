<?php

namespace LeagueOfData\Models\Interfaces;

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
     * Get Spell ID
     */
    public function getSpellID() : int;

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
}

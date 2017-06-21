<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Champion\Champion
 */
interface ChampionInterface
{
    /**
     * Champion ID
     */
    public function getChampionID() : int;

    /**
     * Champion Name
     */
    public function getName() : string;

    /**
     * Champion Title
     */
    public function getTitle() : string;

    /**
     * Client Version
     */
    public function getVersion() : string;

    /**
     * Champion Stats
     */
    public function getStats() : ChampionStatsInterface;

    /**
     * Champion passive
     */
    public function getPassive() : ChampionPassiveInterface;

    /**
     * Champion spells
     */
    public function getSpells() : array;

    /**
     * Champion tags as array
     */
    public function getTags() : array;

    /**
     * Champion tags as original format
     */
    public function getTagsAsString() : string;

    /**
     * Champion resource type
     *
     * @todo Remove and let the actual resource model handle this.
     */
    public function getResourceType() : string;

    /**
     * Region data is for
     */
    public function getRegion() : string;
}

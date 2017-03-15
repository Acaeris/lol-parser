<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Interface
 * 
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Champion
 */
interface ChampionInterface
{
    /**
     * Array of Champion data
     * 
     * @return array
     */
    public function toArray() : array;

    /**
     * Champion ID
     * 
     * @return int
     */
    public function getID() : int;

    /**
     * Champion Name
     * 
     * @return string
     */
    public function getName() : string;

    /**
     * Champion Title
     * 
     * @return string
     */
    public function getTitle() : string;

    /**
     * Client Version
     * 
     * @return string
     */
    public function getVersion() : string;

    /**
     * Champion Stats
     * 
     * @return ChampionStatsInterface
     */
    public function getStats() : ChampionStatsInterface;

    /**
     * Champion tags as array
     * 
     * @return array
     */
    public function getTags() : array;

    /**
     * Champion tags as original format
     * 
     * @return string
     */
    public function getTagsAsString() : string;
}

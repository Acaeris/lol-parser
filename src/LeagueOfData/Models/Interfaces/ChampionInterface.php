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
    public function id() : int;

    /**
     * Champion Name
     * 
     * @return string
     */
    public function name() : string;

    /**
     * Champion Title
     * 
     * @return string
     */
    public function title() : string;

    /**
     * Client Version
     * 
     * @return string
     */
    public function version() : string;

    /**
     * Champion Stats
     * 
     * @return ChampionStatsInterface
     */
    public function stats() : ChampionStatsInterface;

    /**
     * Champion tags as array
     * 
     * @return array
     */
    public function tags() : array;

    /**
     * Champion tags as original format
     * 
     * @return string
     */
    public function tagsAsString() : string;
}

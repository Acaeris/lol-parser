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
     *
     * @return int
     */
    public function getChampionID() : int;

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

    /**
     * Champion resource type
     *
     * @return string
     * @todo Remove and let the actual resource model handle this.
     */
    public function getResourceType() : string;

    /**
     * Region data is for
     *
     * @return string
     */
    public function getRegion() : string;
}

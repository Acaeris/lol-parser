<?php

namespace App\Models\Interfaces;

/**
 * Summoner Interface
 *
 * @author caitlyn.osborne
 */
interface SummonerInterface
{
    /**
     * Summoner ID
     *
     * @return int
     */
    public function getID() : int;

    /**
     * Summoner Name
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Summoner Level
     *
     * @return int
     */
    public function getLevel() : int;

    /**
     * Summoner Icon ID
     *
     * @return int
     */
    public function getIconID() : int;

    /**
     * Revision Date
     *
     * @return string
     */
    public function getRevisionDate() : string;

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Summoner data as an array
     */
    public function toArray() : array;
}

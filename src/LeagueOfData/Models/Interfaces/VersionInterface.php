<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Version Interface
 * 
 * @author caitlyn.osborne
 */
interface VersionInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Version data as an array
     */
    public function toArray() : array;

    /**
     * Full version string
     * 
     * @return string
     */
    public function fullVersion() : string;

    /**
     * Season number
     * 
     * @return int
     */
    public function season() : int;

    /**
     * Major version number
     * 
     * @return int
     */
    public function majorVersion() : int;

    /**
     * Hotfix ID
     * 
     * @return int
     */
    public function hotfix() : int;
}

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

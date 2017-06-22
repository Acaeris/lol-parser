<?php

namespace LeagueOfData\Entity\Version;

/**
 * Version Interface
 *
 * @package LeagueOfData\Models\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface VersionInterface
{
    /**
     * Full version string
     */
    public function getFullVersion() : string;

    /**
     * Season number
     */
    public function getSeason() : int;

    /**
     * Major version number
     */
    public function getMajorVersion() : int;

    /**
     * Hotfix ID
     */
    public function getHotfix() : int;
}

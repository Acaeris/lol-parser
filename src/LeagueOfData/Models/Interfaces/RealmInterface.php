<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Realm Interface
 *
 * @author caitlyn.osborne
 */
interface RealmInterface
{
    /**
     * CDN content url for this realm
     */
    public function getSourceUrl() : string;

    /**
     * Data Dragon version number
     */
    public function getVersion() : string;

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     */
    public function toArray() : array;
}

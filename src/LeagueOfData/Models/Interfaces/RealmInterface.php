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
     *
     * @return string CDN url
     */
    public function sourceUrl() : string;

    /**
     * Data Dragon version number
     *
     * @return string version
     */
    public function version() : string;

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Realm data as an array
     */
    public function toArray() : array;
}

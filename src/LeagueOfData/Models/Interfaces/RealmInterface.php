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
    function sourceUrl() : string;

    /**
     * Data Dragon version number
     *
     * @return string version
     */
    function version() : string;

    /**
     * Realm Region
     *
     * @return string Region
     */
    public function region() : string;
 }

<?php

namespace LeagueOfData\Entity\Realm;

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
}

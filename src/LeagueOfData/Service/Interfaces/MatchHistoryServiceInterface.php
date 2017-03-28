<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\MatchHistory;

/**
 * Match History Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface MatchHistoryServiceInterface
{
    /**
     * Fetch Match History
     *
     * @param string $version
     * @param int    $entryId
     *
     * @return array Match History Objects
     */
    public function fetch(string $version, int $entryId = null) : array;

    /**
     * Store the match history objects in the database
     */
    public function store();

    /**
     * Add a Match History to the collection
     *
     * @param MatchHistory $history
     */
    public function add(MatchHistory $history);

    /**
     * Add all match history objects to internal array
     *
     * @param array $histories Match History objects
     */
    public function addAll(array $histories);
}

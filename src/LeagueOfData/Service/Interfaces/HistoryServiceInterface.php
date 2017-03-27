<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\History;

/**
 * Summoner History service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface HistoryServiceInterface
{
    /**
     * Find all Summoner History data
     *
     * @param int $summonerId Summoner ID
     *
     * @return array History objects
     */
    public function findAll(int $summonerId) : array;

    /**
     * Find a specific Summoner History entry
     *
     * @param int $historyId
     * @return array Item objects
     */
    public function find(int $historyId) : array;

    /**
     * Store the Summoner History objects in the database
     */
    public function store();

    /**
     * Add an entry to the collection
     *
     * @param History $entry
     */
    public function add(History $entry);

    /**
     * Add all entries to internal array
     *
     * @param array $entries History objects
     */
    public function addAll(array $entries);
}

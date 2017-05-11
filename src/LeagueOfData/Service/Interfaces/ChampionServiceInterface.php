<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Adapters\RequestInterface;

/**
 * Champion Service interface
 *
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionServiceInterface
{
    /**
     * Add champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function add(array $champions);

    /**
     * Fetch Champions
     *
     * @param RequestInterface $request
     *
     * @return array Champion Objects
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the champion objects in the database
     */
    public function store();

    /**
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer() : array;

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     *
     * @return Champion
     */
    public function create(array $champion) : Champion;
}

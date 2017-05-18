<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Interfaces\ChampionInterface;
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
     */
    public function add(array $champions);

    /**
     * Fetch Champions
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the champion objects in the database
     */
    public function store();

    /**
     * Get collection of champions for transfer to a different process.
     */
    public function transfer() : array;

    /**
     * Create the champion object from array data
     */
    public function create(array $champion) : ChampionInterface;
}

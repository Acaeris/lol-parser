<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ChampionPassiveInterface;

/**
 * Champion Passives object factory Interface
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionPassivesServiceInterface
{
    /**
     * Add champion passive objects to internal array
     */
    public function add(array $spells);

    /**
     * Factory to create Champion Passive object
     */
    public function create(array $spell) : ChampionPassiveInterface;

    /**
     * Fetch Champion Passives
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the champion passives in the database
     */
    public function store();

    /**
     * Get collection of champions' passives for transfer to a different process.
     */
    public function transfer() : array;
}

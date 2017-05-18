<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;

/**
 * Champion Spells object factory Interface
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ChampionSpellsServiceInterface
{
    /**
     * Add champion spell objects to internal array
     */
    public function add(array $spells);

    /**
     * Factory to create Champion Spells objects
     */
    public function create(array $spell) : ChampionSpellInterface;

    /**
     * Fetch Champion Spells
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the champion spells in the database
     */
    public function store();

    /**
     * Get collection of champions' spells for transfer to a different process.
     */
    public function transfer() : array;
}

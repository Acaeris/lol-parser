<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;

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
     * Factory to create Champion Spells objects
     *
     * @param array $champion
     * @return ChampionSpells
     */
    public function create(array $champion) : array;

    /**
     * Add champion spell objects to internal array
     *
     * @param array $champion
     */
    public function add(array $champion);

    /**
     * Fetch Champion Spells
     *
     * @param RequestInterface $request
     * @return array ChampionSpells Objects
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the champion spells in the database
     */
    public function store();

    /**
     * Get collection of champions' spells for transfer to a different process.
     *
     * @return array ChampionSpells objects
     */
    public function transfer() : array;
}

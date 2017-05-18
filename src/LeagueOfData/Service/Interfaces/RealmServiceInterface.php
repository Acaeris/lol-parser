<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\RealmInterface;

/**
 * Realm Service interface
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface RealmServiceInterface
{
    /**
     * Add realm objects to internal array
     */
    public function add(array $realms);

    /**
     * Factory to create Realm objects
     */
    public function create(array $realm) : RealmInterface;

    /**
     * Fetch all Realm data
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the realms in the database
     */
    public function store();

    /**
     * Get collection of realms for transfer to a different process.
     */
    public function transfer() : array;
}

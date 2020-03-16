<?php
namespace App\Services;

/**
 * Interface for services that fetch and store data
 *
 * @package LeagueOfData\Service
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface StoreServiceInterface
{
    /**
     * Add objects to internal array
     */
    public function add(array $versions);

    /**
     * Store the objects
     */
    public function store();

    /**
     * Find all data
     */
    public function fetch(string $query, array $params = []): array;

    /**
     * Transfer object out to another service
     */
    public function transfer(): array;

    /**
     * Clear the internal collection
     */
    public function clear();
}

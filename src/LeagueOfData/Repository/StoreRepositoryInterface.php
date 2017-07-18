<?php
namespace LeagueOfData\Repository;

use LeagueOfData\Entity\EntityInterface;

/**
 * Interface for repositories that fetch and store data
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface StoreRepositoryInterface
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
     * Transfer object out to another repository
     */
    public function transfer(): array;

    /**
     * Factory for creating objects
     */
    public function create(array $data): EntityInterface;

    /**
     * Clear the internal collection
     */
    public function clear();
}

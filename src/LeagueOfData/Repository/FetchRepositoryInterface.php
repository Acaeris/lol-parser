<?php

namespace LeagueOfData\Repository;

use LeagueOfData\Entity\EntityInterface;

/**
 * Interface for fetch only repositories
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface FetchRepositoryInterface
{
    /**
     * Find all data
     */
    public function fetch(array $params): array;

    /**
     * Transfer object out to another repository
     */
    public function transfer(): array;

    /**
     * Factory for creating objects
     */
    public function create(array $data): EntityInterface;
}

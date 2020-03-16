<?php

namespace App\Services;

/**
 * Interface for fetch only services
 *
 * @package LeagueOfData\Services
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface FetchServiceInterface
{
    /**
     * Find all data
     */
    public function fetch(array $params): array;

    /**
     * Transfer object out to another service
     */
    public function transfer(): array;
}

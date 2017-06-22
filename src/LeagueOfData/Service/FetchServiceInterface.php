<?php

namespace LeagueOfData\Service;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Entity\EntityInterface;

/**
 * Interface for fetch only services
 *
 * @package LeagueOfData\Service
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface FetchServiceInterface
{
    /**
     * Find all data
     */
    public function fetch(RequestInterface $request): array;

    /**
     * Transfer object out to another service
     */
    public function transfer(): array;

    /**
     * Factory for creating objects
     */
    public function create(array $data): EntityInterface;
}

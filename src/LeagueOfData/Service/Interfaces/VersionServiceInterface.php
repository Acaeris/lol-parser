<?php
namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\VersionInterface;

/**
 * Version Service interface
 *
 * @package LeagueOfData\Service\Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface VersionServiceInterface
{
    /**
     * Add version objects to internal array
     */
    public function add(array $versions);

    /**
     * Factory for creating version objects
     */
    public function create(string $version) : VersionInterface;

    /**
     * Find all Version data
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the version objects in the DB
     */
    public function store();

    /**
     * Transfer objects out to another service
     */
    public function transfer() : array;
}

<?php
namespace LeagueOfData\Service\Interfaces;

/**
 * Version Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface VersionServiceInterface
{
    /**
     * Add a version object to internal array
     *
     * @param Version $version
     */
    public function add(Version $version);

    /**
     * Add all version objects to internal array
     *
     * @param array $versions Version objects
     */
    public function addAll(array $versions);

    /**
     * Store the version objects in the DB
     */
    public function store();

    /**
     * Find all Version data
     *
     * @return array Version objects
     */
    public function findAll() : array;

    /**
     * Transfer objects out to another service
     *
     * @return array Version objects
     */
    public function transfer() : array;
}

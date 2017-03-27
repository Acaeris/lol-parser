<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Item\Item;

/**
 * Item Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ItemServiceInterface
{

    /**
     * Find all Item data
     *
     * @param string $version Version number
     * @return array Item objects
     */
    public function findAll(string $version) : array;

    /**
     * Find a specific item
     *
     * @param string $version
     * @param int    $itemId
     * @return array Item objects
     */
    public function find(string $version, int $itemId) : array;

    /**
     * Store the item objects in the database
     */
    public function store();

    /**
     * Add an item to the collection
     *
     * @param Item $item
     */
    public function add(Item $item);
}

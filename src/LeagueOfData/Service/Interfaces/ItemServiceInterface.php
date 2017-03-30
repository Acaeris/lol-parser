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
     * Fetch Items
     *
     * @param string $version
     * @param int    $itemId
     *
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null) : array;

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

    /**
     * Add all item objects to internal array
     *
     * @param array $items Item objects
     */
    public function addAll(array $items);
}

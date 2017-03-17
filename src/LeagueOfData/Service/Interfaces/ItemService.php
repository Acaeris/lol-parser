<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Models\Item\Item;

interface ItemService {

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
     * @param int $itemId
     * @return array Item objects
     */
    public function find(string $version, int $itemId) : array;

    /**
     * Store the item objects in the database
     */
    public function store();

    /**
     * Fetch Items
     * 
     * @param string $version
     * @param int $itemId
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null) : array;

    /**
     * Add an item to the collection
     * 
     * @param Item $item
     */
    public function add(Item $item);
}

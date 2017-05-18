<?php

namespace LeagueOfData\Service\Interfaces;

use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ItemInterface;

/**
 * Item Service interface
 *
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ItemServiceInterface
{
    /**
     * Add item objects to internal array
     */
    public function add(array $items);

    /**
     * Factory to create Item objects
     */
    public function create(array $item, array $stats) : ItemInterface;
    
    /**
     * Fetch Items
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Store the item objects in the database
     */
    public function store();

    /**
     * Get collection of items for transfer to a different process
     */
    public function transfer() : array;
}

<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Item Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\Item\Item
 */
interface ItemInterface
{
    /**
     * Array of Item data
     *
     * @return array
     */
    public function toArray() : array;
}

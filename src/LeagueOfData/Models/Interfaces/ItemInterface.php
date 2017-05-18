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
     * Item ID
     */
    public function getItemID() : int;

    /**
     * Item Name
     */
    public function getName() : string;

    /**
     * Item Description
     */
    public function getDescription() : string;

    /**
     * Item purchase cost
     */
    public function getGoldToBuy() : int;

    /**
     * Item sale value
     */
    public function getGoldFromSale() : int;

    /**
     * Fetch the item stats
     */
    public function getStats() : array;

    /**
     * Fetch a specific stat
     */
    public function getStat(string $key) : float;

    /**
     * Data version of item
     */
    public function getVersion() : string;

    /**
     * Region data is for
     */
    public function getRegion() : string;
}

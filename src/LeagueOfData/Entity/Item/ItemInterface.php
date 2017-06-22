<?php

namespace LeagueOfData\Entity\Item;

use LeagueOfData\Entity\EntityInterface;

/**
 * Item Interface
 *
 * @author caitlyn.osborne
 */
interface ItemInterface extends EntityInterface
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

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
     *
     * @return int
     */
    public function getID() : int;

    /**
     * Item Name
     *
     * @return string
     */
    public function name() : string;

    /**
     * Item Description
     *
     * @return string
     */
    public function description() : string;

    /**
     * Item purchase cost
     *
     * @return int
     */
    public function goldToBuy() : int;

    /**
     * Item sale value
     *
     * @return int
     */
    public function goldFromSale() : int;

    /**
     * Data version of item
     *
     * @return string
     */
    public function version() : string;

    /**
     * Fetch the item stats
     *
     * @return array
     */
    public function stats() : array;

    /**
     * Fetch a specific stat
     *
     * @param string $key
     *
     * @return float
     */
    public function getStat(string $key) : float;

    /**
     * Region data is for
     *
     * @return string
     */
    public function region() : string;
}

<?php

namespace LeagueOfData\Entity\Item;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Item implements ItemInterface, ImmutableInterface
{

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var int Item ID
     */
    private $itemID;

    /**
     * @var string Item Name
     */
    private $name;

    /**
     * @var string Item Description
     */
    private $description;

    /**
     * @var int Gold purchase value
     */
    private $purchaseValue;

    /**
     * @var int Gold sale value
     */
    private $saleValue;

    /**
     * @var array Item stats
     */
    private $stats;

    /**
     * @var string version
     */
    private $version;

    /**
     * @var string region
     */
    private $region;

    public function __construct(
        int $itemID,
        string $name,
        string $description,
        int $purchaseValue,
        int $saleValue,
        array $stats,
        string $version,
        string $region
    ) {

        $this->constructImmutable();

        $this->name = $name;
        $this->itemID = $itemID;
        $this->purchaseValue = $purchaseValue;
        $this->saleValue = $saleValue;
        $this->description = $description;
        $this->version = $version;
        $this->stats = $stats;
        $this->region = $region;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData() : array
    {
        return [
            'item_id' => $this->itemID,
            'version' => $this->version,
            'region' => $this->region
        ];
    }

    /**
     * Item ID
     *
     * @return int
     */
    public function getItemID() : int
    {
        return $this->itemID;
    }

    /**
     * Item Name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Item Description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Item purchase cost
     *
     * @return int
     */
    public function getGoldToBuy() : int
    {
        return $this->purchaseValue;
    }

    /**
     * Item sale value
     *
     * @return int
     */
    public function getGoldFromSale() : int
    {
        return $this->saleValue;
    }

    /**
     * Fetch the item stats
     *
     * @return array
     */
    public function getStats() : array
    {
        return $this->stats;
    }

    /**
     * Fetch a specific stat
     *
     * @param  string $key
     * @return float
     */
    public function getStat(string $key) : float
    {
        foreach ($this->stats as $stat) {
            if ($stat->getStatName() === $key) {
                return $stat->getStatModifier();
            }
        }

        return 0;
    }

    /**
     * Data version of item
     *
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * Region data is for
     *
     * @return string
     */
    public function getRegion() : string
    {
        return $this->region;
    }
}

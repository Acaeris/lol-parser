<?php

namespace LeagueOfData\Models\Item;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ItemInterface;

class Item implements ItemInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /* @var int Item ID */
    private $itemID;
    
    /* @var string Item Name */
    private $name;

    /* @var string Item Description */
    private $description;

    /* @var int Gold purchase value */
    private $purchaseValue;

    /* @var int Gold sale value */
    private $saleValue;

    /* @var string version */
    private $version;

    public function __construct(int $itemID, string $name, string $description, int $purchaseValue,
        int $saleValue, string $version)
    {
        $this->constructImmutable();

        $this->name = $name;
        $this->itemID = $itemID;
        $this->purchaseValue = $purchaseValue;
        $this->saleValue = $saleValue;
        $this->description = $description;
        $this->version = $version;
    }

    /**
     * Array of Item data
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->itemID,
            'name' => $this->name,
            'description' => $this->description,
            'purchaseValue' => $this->purchaseValue,
            'saleValue' => $this->saleValue,
            'version' => $this->version
        ];
    }

    /**
     * Item ID
     *
     * @return int
     */
    public function getID() : int
    {
        return $this->itemID;
    }

    /**
     * Item Name
     *
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * Item Description
     *
     * @return string
     */
    public function description() : string
    {
        return $this->description;
    }

    /**
     * Item purchase cost
     *
     * @return int
     */
    public function goldToBuy() : int
    {
        return $this->purchaseValue;
    }

    /**
     * Item sale value
     *
     * @return int
     */
    public function goldFromSale() : int
    {
        return $this->saleValue;
    }

    /**
     * Data version of item
     *
     * @return string
     */
    public function version() : string
    {
        return $this->version;
    }
}

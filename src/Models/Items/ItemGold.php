<?php

namespace App\Models\Items;

class ItemGold
{
    /* @var int Base item value */
    private $baseValue;

    /* @var int Total item value */
    private $totalValue;

    /* @var int Item sale value */
    private $sellValue;

    /* @var bool Puchasable? */
    private $purchasable;

    /**
     * @param int $baseValue Base value
     * @param int $totalValue Total value
     * @param int $sellValue Sale value
     * @param bool $purchasable Is the item purchasable?
     */
    public function __construct(
        int $baseValue,
        int $totalValue,
        int $sellValue,
        bool $purchasable
    ) {
        $this->baseValue = $baseValue;
        $this->totalValue = $totalValue;
        $this->sellValue = $sellValue;
        $this->purchasable = $purchasable;
    }

    /**
     * @return int Base value
     */
    public function getBaseValue(): int
    {
        return $this->baseValue;
    }

    /**
     * @return int Total value
     */
    public function getTotalValue(): int
    {
        return $this->totalValue;
    }

    /**
     * @return int Sale value
     */
    public function getSellValue(): int
    {
        return $this->sellValue;
    }

    /**
     * @return bool Is the item purchasable
     */
    public function isPurchasable(): bool
    {
        return $this->purchasable;
    }
}

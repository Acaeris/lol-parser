<?php

namespace App\Models\Champions;

class ChampionBlockItem
{
    /**
     * Item ID
     *
     * @var int
     */
    private $itemId;

    /**
     * Item count
     *
     * @var int
     */
    private $count;

    /**
     * @param int $itemId - Item ID
     * @param int $count - Amount recommended to buy
     */
    public function __construct(int $itemId, int $count)
    {
        $this->itemId = $itemId;
        $this->count = $count;
    }

    /**
     * @return int - Item ID
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @return int - Amount recommended to buy
     */
    public function getCount(): int
    {
        return $this->count;
    }
}

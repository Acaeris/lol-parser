<?php

namespace App\Models\Champions;

class ChampionBlock
{
    /**
     * Items
     *
     * @var ChampionBlockItem[]
     */
    private $items;

    /**
     * Type
     *
     * @var string
     */
    private $type;

    /**
     * RecMath
     *
     * @var bool
     */
    private $recMath;

    /**
     * @param ChampionBlockItem[] $items - Items
     * @param string $type - Recommendation type
     * @param bool $recMath - ??
     */
    public function __construct(array $items, string $type, bool $recMath)
    {
        $this->items = $items;
        $this->type = $type;
        $this->recMath = $recMath;
    }

    /**
     * @return ChampionBlockItem[] - Items
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return string - Recommendation type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool - ??
     */
    public function isRecMath(): bool
    {
        return $this->recMath;
    }
}

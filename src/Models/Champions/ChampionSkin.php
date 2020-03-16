<?php

namespace App\Models\Champions;

class ChampionSkin
{
    /**
     * Champion ID
     *
     * @var int
     */
    private $championId;

    /**
     * Skin number
     *
     * @var int
     */
    private $number;

    /**
     * Skin name
     *
     * @var string
     */
    private $skinName;

    public function __construct(int $championId, int $number, string $skinName)
    {
        $this->championId = $championId;
        $this->number = $number;
        $this->skinName = $skinName;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getChampionId(): int
    {
        return $this->championId;
    }

    public function getSkinName(): string
    {
        return $this->skinName;
    }
}

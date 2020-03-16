<?php

namespace App\Models\Champions;

class ChampionInfo
{
    /**
     * Champion difficulty
     *
     * @var int
     */
    private $difficulty;

    /**
     * Champion Attack Rating
     *
     * @var int
     */
    private $attack;

    /**
     * Champion Defense Rating
     *
     * @var int
     */
    private $defense;

    /**
     * Champion Magic Rating
     *
     * @var int
     */
    private $magic;

    public function __construct(
        int $difficulty,
        int $attack,
        int $defense,
        int $magic
    ) {
        $this->difficulty = $difficulty;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->magic = $magic;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function getAttack(): int
    {
        return $this->attack;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getMagic()
    {
        return $this->magic;
    }
}

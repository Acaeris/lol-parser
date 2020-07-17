<?php

namespace App\Models\ChampionMastery;

class ChampionMastery
{
    /* @var int Champion ID */
    private $championId;

    /* @var string Encrypted Summoner ID */
    private $summonerId;

    /* @var int Champion Level */
    private $championLevel;

    /* @var bool has Mastery Chest been granted */
    private $chestGranted;

    /* @var int Champion Mastery Points */
    private $championPoints;

    /* @var int Champion Mastery Points since last level */
    private $championPointsSinceLastLevel;

    /* @var int Champion Mastery Points until next level */
    private $championPointsUntilNextLevel;

    /* @var int Number of Mastery Tokens earned */
    private $tokensEarned;

    /* @var int Time the champion was last played */
    private $lastPlayTime;

    public function __construct(
        int $championId,
        string $summonerId,
        int $championLevel,
        bool $chestGranted,
        int $championPoints,
        int $championPointsSinceLastLevel,
        int $championPointsUntilNextLevel,
        int $tokensEarned,
        int $lastPlayTime
    ) {
        $this->championId = $championId;
        $this->summonerId = $summonerId;
        $this->championLevel = $championLevel;
        $this->chestGranted = $chestGranted;
        $this->championPoints = $championPoints;
        $this->championPointsSinceLastLevel = $championPointsSinceLastLevel;
        $this->championPointsUntilNextLevel = $championPointsUntilNextLevel;
        $this->tokensEarned = $tokensEarned;
        $this->lastPlayTime = $lastPlayTime;
    }

    public function getChampionId(): int
    {
        return $this->championId;
    }

    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    public function getLevel(): int
    {
        return $this->championLevel;
    }

    public function isChestAwarded(): bool
    {
        return $this->chestGranted;
    }

    public function getMasteryPoints(): int
    {
        return $this->championPoints;
    }

    public function getMasteryPointsThisLevel(): int
    {
        return $this->championPointsSinceLastLevel;
    }

    public function getMasteryPointsNextLevel(): int
    {
        return $this->championPointsUntilNextLevel;
    }

    public function getTokensEarned(): int
    {
        return $this->tokensEarned;
    }

    public function getLastTimePlayed(): int
    {
        return $this->lastPlayTime;
    }
}

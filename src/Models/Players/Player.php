<?php

namespace App\Models\Players;

class Player
{
    /* @var int Account ID */
    private $accountId;

    /* @var int Current account ID */
    private $currentAccountId;

    /* @var int Platform ID */
    private $platformId;

    /* @var int Current platform ID */
    private $currentPlatformId;

    /* @var int Summoner ID */
    private $summonerId;

    /* @var string Summoner name */
    private $summonerName;

    /* @var int Profile icon */
    private $profileIcon;

    /* @var string Match history URI */
    private $matchHistoryUri;

    /**
     * @param int $accountId - Account ID
     * @param int $currentAccountId - Current account ID
     * @param int $platformId - Platform ID
     * @param int $currentPlatformId - Current platform ID
     * @param int $summonerId - Summoner ID
     * @param string $summonerName - Summoner name
     * @param int $profileIcon - Profile icon
     * @param string $matchHistoryUri - Match history URI
     */
    public function __construct(
        int $accountId,
        int $currentAccountId,
        int $platformId,
        int $currentPlatformId,
        int $summonerId,
        string $summonerName,
        int $profileIcon,
        string $matchHistoryUri
    ) {
        $this->accountId = $accountId;
        $this->currentAccountId = $currentAccountId;
        $this->platformId = $platformId;
        $this->currentPlatformId = $currentPlatformId;
        $this->summonerId = $summonerId;
        $this->summonerName = $summonerName;
        $this->profileIcon = $profileIcon;
        $this->matchHistoryUri = $matchHistoryUri;
    }

    /**
     * @return int - Account ID
     */
    public function getAccountID(): int
    {
        return $this->accountId;
    }

    /**
     * @return int - Current account ID
     */
    public function getCurrentAccountID(): int
    {
        return $this->currentAccountId;
    }

    /**
     * @return int - Platform ID
     */
    public function getPlatformID(): int
    {
        return $this->platformId;
    }

    /**
     * @return int - Current platform ID
     */
    public function getCurrentPlatformID(): int
    {
        return $this->currentPlatformId;
    }

    /**
     * @return int - Summoner ID
     */
    public function getSummonerID(): int
    {
        return $this->summonerId;
    }

    /**
     * @return string - Summoner name
     */
    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    /**
     * @return int - Profile icon
     */
    public function getProfileIcon(): int
    {
        return $this->profileIcon;
    }

    /**
     * @return string - Match history URI
     */
    public function getMatchHistoryUri(): string
    {
        return $this->matchHistoryUri;
    }
}

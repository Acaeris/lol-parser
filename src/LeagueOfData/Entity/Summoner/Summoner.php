<?php

namespace LeagueOfData\Entity\Summoner;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Summoner implements SummonerInterface, ImmutableInterface
{

    /**
     * @var string Region
     */
    private $region;

    /**
     * @var DateTime Revision Date
     */
    private $revisionDate;

    /**
     * @var int Profile Icon ID
     */
    private $profileIconID;

    /**
     * @var int Summoner Level
     */
    private $level;

    /**
     * @var string Summoner Name
     */
    private $name;

    /**
     * @var int Account ID
     */
    private $accountID;

    /**
     * @var int Summoner ID
     */
    private $summonerID;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $summonerID,
        int $accountID,
        string $name,
        int $level,
        int $profileIconID,
        string $revisionDate,
        string $region
    ) {
        $this->constructImmutable();

        $this->summonerID = $summonerID;
        $this->accountID = $accountID;
        $this->name = $name;
        $this->level = $level;
        $this->profileIconID = $profileIconID;
        $this->revisionDate = new \DateTime($revisionDate);
        $this->region = $region;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData(): array
    {
        return [
            'summoner_id' => $this->summonerID,
            'region' => $this->region
        ];
    }

    /**
     * Summoner ID
     *
     * @return int
     */
    public function getSummonerID() : int
    {
        return $this->summonerID;
    }

    /**
     * Account ID
     *
     * @return int
     */
    public function getAccountID() : int
    {
        return $this->accountID;
    }

    /**
     * Summoner Name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Summoner Level
     *
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * Profile Icon ID
     *
     * @return int
     */
    public function getProfileIconID() : int
    {
        return $this->profileIconID;
    }

    /**
     * Revision Date
     *
     * @return DateTime
     */
    public function getRevisionDate() : \DateTime
    {
        return $this->revisionDate;
    }

    /**
     * Region
     *
     * @return string
     */
    public function getRegion() : string
    {
        return $this->region;
    }
}

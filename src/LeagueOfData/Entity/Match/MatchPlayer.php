<?php

namespace LeagueOfData\Entity\Match;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class MatchPlayer implements MatchPlayerInterface, ImmutableInterface
{

    /**
     * @var int
     */
    private $championID;

    /**
     * @var string
     */
    private $region;

    /**
     * @var int
     */
    private $accountID;

    /**
     * @var int
     */
    private $matchId;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(int $matchId, int $accountId, int $championId, string $region)
    {
        $this->constructImmutable();
        $this->matchId = $matchId;
        $this->accountID = $accountId;
        $this->region = $region;
        $this->championID = $championId;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData(): array
    {
        return [
            'match_id' => $this->matchId,
            'account_id' => $this->accountID,
            'region' => $this->region
        ];
    }

    /**
     * Match ID
     *
     * @return int
     */
    public function getMatchID(): int
    {
        return $this->matchId;
    }

    /**
     * Account ID
     *
     * @return int
     */
    public function getAccountID(): int
    {
        return $this->accountID;
    }

    /**
     * Champion ID
     *
     * @return int
     */
    public function getChampionID(): int
    {
        return $this->championID;
    }

    /**
     * Region
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }
}

<?php

namespace LeagueOfData\Entity\Match;

use LeagueOfData\Entity\Match\MatchInterface;
use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Match implements MatchInterface, ImmutableInterface
{

    /**
     * @var string
     */
    private $gameVersion;

    /**
     * @var string
     */
    private $gameMode;

    /**
     * @var string
     */
    private $region;

    /**
     * @var int
     */
    private $matchID;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $matchID,
        string $region,
        string $gameMode = null,
        string $gameVersion = null
    ) {
        $this->constructImmutable();

        $this->matchID = $matchID;
        $this->region = $region;
        $this->gameMode = $gameMode;
        $this->gameVersion = $gameVersion;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData(): array
    {
        return [
            'match_id' => $this->matchID,
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
        return $this->matchID;
    }

    /**
     * Game Mode
     *
     * @return string
     */
    public function getGameMode(): string
    {
        return $this->gameMode;
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

    /**
     * Game Version
     *
     * @return string
     */
    public function getGameVersion(): string
    {
        return $this->gameVersion;
    }
}

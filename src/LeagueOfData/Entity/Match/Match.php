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
    private $duration;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $mode;

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
        string $mode,
        string $type,
        int $duration,
        string $version,
        string $region
    ) {
        $this->constructImmutable();

        $this->matchID = $matchID;
        $this->region = $region;
        $this->mode = $mode;
        $this->version = $version;
        $this->type = $type;
        $this->duration = $duration;
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
    public function getMode(): string
    {
        return $this->mode;
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
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Game Type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Game Duration
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}

<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\SummonerInterface;

final class Summoner implements SummonerInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var type int Summoner ID
     */
    private $summonerID;

    /**
     * @var string Summoner Name
     */
    private $name;

    /**
     * @var int Summoner Level 
     */
    private $level;

    /**
     * @var int Summoner Icon ID
     */
    private $iconID;

    /**
     * @var date Revision Date 
     */
    private $revisionDate;

    /**
     * Creates a new Summoner from an existing state.
     * Use as an alternative constructor as PHP does not support multiple constructors.
     * 
     * @param array $summoner Data from an existing state (e.g. SQL result, Json, or object converted to array)
     * @return SummonerInterface Resultant Summoner object
     */
    public static function fromState(array $summoner) : SummonerInterface
    {
        return new self(
            $summoner['id'],
            $summoner['name'],
            $summoner['level'],
            $summoner['icon'],
            $summoner['revisionDate']
        );
    }

    /**
     * Create a summoner object
     * 
     * @param int $summonerID Summoner ID
     * @param string $name Summoner Name
     * @param int $level Summoner Level
     * @param int $iconID Summoner Icon ID
     * @param string $revisionDate Revision Date
     */
    public function __construct(int $summonerID, string $name, int $level, int $iconID, string $revisionDate)
    {
        $this->constructImmutable();

        $this->summonerID = $summonerID;
        $this->name = $name;
        $this->level = $level;
        $this->iconID = $iconID;
        $this->revisionDate = $revisionDate;
    }

    /**
     * Summoner ID
     * 
     * @return int
     */
    public function getID() : int
    {
        return $this->summonerID;
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
     * Summoner Icon ID
     * 
     * @return int
     */
    public function getIconID() : int
    {
        return $this->iconID;
    }

    /**
     * Revision Date
     * 
     * @return string
     */
    public function getRevisionDate() : string
    {
        return $this->revisionDate;
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Summoner data as an array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->summonerID,
            'name' => $this->name,
            'level' => $this->level,
            'icon' => $this->iconID,
            'revisionDate' => $this->revisionDate
        ];
    }

}

<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ChampionInterface;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

final class Champion implements ChampionInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var int Champion ID
     */
    private $championId;

    /**
     * @var string Champion Name
     */
    private $name;

    /**
     * @var string Champion Title
     */
    private $title;

    /**
     * @var string Client Version
     */
    private $version;

    /**
     * @var ChampionStats Champion statistics
     */
    private $stats;

    /**
     * @var array Champion type tags
     */
    private $tags;

    /**
     * @var string Type of resource used by champion
     */
    private $resourceType;

    /**
     * @var string Region data is for
     */
    private $region;

    /**
     * Construct a Champion object
     *
     * @param int                    $championId   Champion ID
     * @param string                 $name         Champion Name
     * @param string                 $title        Champion Title
     * @param string                 $resourceType Champion Resource Type
     * @param string                 $tags         Class tags
     * @param ChampionStatsInterface $stats        Statistics
     * @param string                 $version      Full version number
     * @param string                 $region       Region data is for
     */
    public function __construct(
        int $championId,
        string $name,
        string $title,
        string $resourceType,
        array $tags,
        ChampionStatsInterface $stats,
        string $version,
        string $region
    ) {
        $this->constructImmutable();

        $this->championId = $championId;
        $this->name = $name;
        $this->title = $title;
        $this->resourceType = $resourceType;
        $this->version = $version;
        $this->stats = $stats;
        $this->tags = $tags;
        $this->region = $region;
    }

    /**
     * Champion ID
     *
     * @return int
     */
    public function getID() : int
    {
        return $this->championId;
    }

    /**
     * Champion Name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Champion Title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Client Version
     *
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * Champion Stats
     *
     * @return ChampionStatsInterface
     */
    public function getStats() : ChampionStatsInterface
    {
        return $this->stats;
    }

    /**
     * Champion tags as array
     *
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    /**
     * Champion tags as original format
     *
     * @return string
     */
    public function getTagsAsString() : string
    {
        return implode("|", $this->tags);
    }

    /**
     * Champion resource type
     *
     * @return string
     * @todo Remove and let the actual resource model handle this.
     */
    public function getResourceType() : string
    {
        return $this->resourceType;
    }

    /**
     * Region data is for
     *
     * @return string
     */
    public function getRegion() : string
    {
        return $this->region;
    }
}

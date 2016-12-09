<?php

namespace LeagueOfData\Models;

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
    private $id;

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
     * Factory Construction
     * 
     * @param array $champion
     * @return ChampionInterface
     */
    public static function fromState(array $champion): ChampionInterface
    {
        return new self(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $champion['resourceType'],
            $champion['tags'],
            ChampionStats::fromState($champion),
            $champion['version']
        );
    }

    /**
     * Main Constructor
     * 
     * @param int $id
     * @param string $name
     * @param string $title
     * @param string $resourceType
     * @param string $tags
     * @param ChampionStatsInterface $stats
     * @param string $version
     */
    public function __construct(
        int $id,
        string $name,
        string $title,
        string $resourceType,
        string $tags,
        ChampionStatsInterface $stats,
        string $version
    ) {
        $this->constructImmutable();

        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->resourceType = $resourceType;
        $this->version = $version;
        $this->stats = $stats;
        $this->tags = $tags;
    }

    /**
     * Array of Champion data
     * 
     * @return array
     */
    public function toArray() : array
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'resourceType' => $this->resourceType,
            'tags' => $this->tags,
            'version' => $this->version
        ], $this->stats->toArray());
    }

    /**
     * Champion ID
     * 
     * @return int
     */
    public function id() : int
    {
        return $this->id;
    }

    /**
     * Champion Name
     * 
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * Champion Title
     * 
     * @return string
     */
    public function title() : string
    {
        return $this->title;
    }

    /**
     * Client Version
     * 
     * @return string
     */
    public function version() : string
    {
        return $this->version;
    }

    /**
     * Champion Stats
     * 
     * @return ChampionStatsInterface
     */
    public function stats() : ChampionStatsInterface
    {
        return $this->stats;
    }

    /**
     * Champion tags as array
     * 
     * @return array
     */
    public function tags() : array
    {
        return explode('|', $this->tags);
    }

    /**
     * Champion tags as original format
     * 
     * @return string
     */
    public function tagsAsString() : string
    {
        return $this->tags;
    }
}

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
     * Creates a new Champion Defence from an existing state.
     * Use as an alternative constructor as PHP does not support multiple constructors.
     *
     * @param array $champion Data from an existing state (e.g. SQL result, Json, or object converted to array)
     * @return ChampionInterface Resultant Champion
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
     * Construct a Champion object
     *
     * @param int $id Champion ID
     * @param string $name Champion Name
     * @param string $title Champion Title
     * @param string $resourceType Champion Resource Type
     * @param string $tags Class tags
     * @param ChampionStatsInterface $stats Statistics
     * @param string $version Full version number
     */
    public function __construct(
        int $id,
        string $name,
        string $title,
        string $resourceType,
        array $tags,
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
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion data as an array
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
    public function getID() : int
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
        return $this->tags;
    }

    /**
     * Champion tags as original format
     *
     * @return string
     */
    public function tagsAsString() : string
    {
        return implode("|", $this->tags);
    }

    /**
     * Champion resource type
     *
     * @return string
     * @todo Remove and let the actual resource model handle this.
     */
    public function resourceType() : string
    {
        return $this->resourceType;
    }
}
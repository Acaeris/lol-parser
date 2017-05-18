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
     * @var string Name used for champion images
     */
    private $imageName;

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
     * @param string                 $imageName    Champion Image Name
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
        string $imageName,
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
        $this->imageName = $imageName;
    }

    /**
     * Champion ID
     *
     * @return int
     */
    public function getChampionID() : int
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
     * Champion Image Name
     *
     * @return string
     */
    public function getImageName() : string
    {
        return $this->imageName;
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
    
    /**
     * MOVE THE FOLLOWING TO MORE APPROPRIATE MODELS ONCE IMPLEMENTED
     */
    public function getPlayStats() : array
    {
        return [
            ['label' => 'Win Rate', 'value' => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 100, 2)."%"],
            ['label' => 'Popularity', 'value' => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 100, 2)."%"],
            ['label' => 'Ban Rate', 'value' => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 100, 2)."%"],
            ['label' => 'AVG Matches Played', 'value' => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 10, 2)]
        ];
    }

    public function getRating() : array
    {
        return [
            "early" => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 5),
            "mid" => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 5),
            "late" => round(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() * 5)
        ];
    }

    public function getPassive() : array
    {
        return [
            "id" => 1,
            "name" => "Test Passive",
            "key" => "passive",
            "file" => str_replace(' ', '', $this->name)."_Passive",
            "description" => "Test Passive's description"
        ];
    }

    public function getSpells() : array
    {
        return [
            [
                "id" => 1,
                "name" => "Test Q",
                "key" => "Q",
                "file" => str_replace(' ', '', $this->name)."Q",
                "description" => "Test Q's description",
                "maxrank" => 5,
                "tooltip" => "Test Q's tooltip {{ e1 }} <span class=\"color99FF99\">(+{{ a1 }})</span>",
                "cooldown" => [4, 4, 4, 4, 4],
                "range" => [625, 625, 625, 625, 625],
                "effect" => [null,[80,115,150,185,220],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],
                "vars" => [
                    [
                        "link" => "spelldamage",
                        "coeff" => [0.8, 0.8, 0.8, 0.8, 0.8],
                        "key" => "a1"
                    ]
                ],
                "resource" => [
                    "name" => "Mana",
                    "values" => [60, 65, 70, 75, 80]
                ]
            ], [
                "id" => 1,
                "name" => "Test W",
                "key" => "W",
                "file" => str_replace(' ', '', $this->name)."W",
                "description" => "Test W's description",
                "maxrank" => 5,
                "tooltip" => "Test W's tooltip",
                "cooldown" => [4, 4, 4, 4, 4],
                "range" => [625, 625, 625, 625, 625],
                "effect" => [null,[80,115,150,185,220],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],
                "vars" => [
                    [
                        "link" => "spelldamage",
                        "coeff" => [0.8, 0.8, 0.8, 0.8, 0.8],
                        "key" => "a1"
                    ]
                ],
                "resource" => [
                    "name" => "Mana",
                    "values" => [60, 65, 70, 75, 80]
                ]
            ], [
                "id" => 1,
                "name" => "Test E",
                "key" => "E",
                "file" => str_replace(' ', '', $this->name)."E",
                "description" => "Test E's description",
                "maxrank" => 5,
                "tooltip" => "Test E's tooltip",
                "cooldown" => [4, 4, 4, 4, 4],
                "range" => [625, 625, 625, 625, 625],
                "effect" => [null,[80,115,150,185,220],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],
                "vars" => [
                    [
                        "link" => "spelldamage",
                        "coeff" => [0.8, 0.8, 0.8, 0.8, 0.8],
                        "key" => "a1"
                    ]
                ],
                "resource" => [
                    "name" => "Mana",
                    "values" => [60, 65, 70, 75, 80]
                ]
            ], [
                "id" => 1,
                "name" => "Test R",
                "key" => "R",
                "file" => str_replace(' ', '', $this->name)."R",
                "description" => "Test R's description",
                "maxrank" => 3,
                "tooltip" => "Test R's tooltip",
                "cooldown" => [4, 4, 4],
                "range" => [625, 625, 625],
                "effect" => [null,[80,115,150],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0]],
                "vars" => [
                    [
                        "link" => "spelldamage",
                        "coeff" => [0.8, 0.8, 0.8],
                        "key" => "a1"
                    ]
                ],
                "resource" => [
                    "name" => "Mana",
                    "values" => [60, 65, 70]
                ]
            ]
        ];
    }

    public function getCombos() : array
    {
        return [
            [
                "title" => "Test",
                "spells" => [
                    [
                        "id" => 1,
                        "name" => str_replace(' ', '', $this->name)."Q",
                        "key" => "Q"
                    ], [
                        "id" => 1,
                        "name" => str_replace(' ', '', $this->name)."W",
                        "key" => "W"
                    ], [
                        "id" => 1,
                        "name" => str_replace(' ', '', $this->name)."E",
                        "key" => "E"
                    ], [
                        "id" => 1,
                        "name" => str_replace(' ', '', $this->name)."R",
                        "key" => "R"
                    ]
                ]
            ]
        ];
    }
}

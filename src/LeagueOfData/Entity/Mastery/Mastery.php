<?php

namespace LeagueOfData\Entity\Mastery;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Mastery implements MasteryInterface, ImmutableInterface
{

    /**
     * @var string
     */
    private $masteryTree;

    /**
     * @var int
     */
    private $ranks;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var array
     */
    private $description;

    /**
     * @var string
     */
    private $masteryName;

    /**
     * @var int
     */
    private $masteryID;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $masteryID,
        string $masteryName,
        array $description,
        int $ranks,
        string $imageName,
        string $masteryTree,
        string $version,
        string $region
    ) {
        $this->constructImmutable();
        $this->masteryID = $masteryID;
        $this->masteryName = $masteryName;
        $this->description = $description;
        $this->imageName = $imageName;
        $this->version = $version;
        $this->region = $region;
        $this->ranks = $ranks;
        $this->masteryTree = $masteryTree;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData() : array
    {
        return [
            'mastery_id' => $this->masteryID,
            'version' => $this->version,
            'region' => $this->region
        ];
    }

    /**
     * Mastery ID
     *
     * @return int
     */
    public function getMasteryID() : int
    {
        return $this->masteryID;
    }

    /**
     * Mastery Name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->masteryName;
    }

    /**
     * Description
     *
     * @return array
     */
    public function getDescription() : array
    {
        return $this->description;
    }

    /**
     * Image Name
     *
     * @return string
     */
    public function getImageName() : string
    {
        return $this->imageName;
    }

    /**
     * Ranks
     *
     * @return int
     */
    public function getRanks()  : int
    {
        return $this->ranks;
    }

    /**
     * Mastery Tree
     *
     * @return string
     */
    public function getMasteryTree() : string
    {
        return $this->masteryTree;
    }

    /**
     * Version
     *
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
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

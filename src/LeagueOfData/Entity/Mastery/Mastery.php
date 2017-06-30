<?php

namespace LeagueOfData\Entity\Mastery;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Mastery implements MasteryInterface, ImmutableInterface
{

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var array
     */
    private $stats;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
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
        string $description,
        string $imageName,
        array $stats,
        array $tags,
        string $version,
        string $region
    ) {
        $this->constructImmutable();
        $this->masteryID = $masteryID;
        $this->masteryName = $masteryName;
        $this->description = $description;
        $this->imageName = $imageName;
        $this->stats = $stats;
        $this->tags = $tags;
        $this->version = $version;
        $this->region = $region;
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
     * @return string
     */
    public function getDescription() : string
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
     * Rune Stats
     *
     * @return array
     */
    public function getStats() : array
    {
        return $this->stats;
    }

    /**
     * Rune Tags
     *
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
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

    /**
     * Fetch a specific stat
     *
     * @param string $key
     * @return float
     */
    public function getStat(string $key) : float
    {
        foreach ($this->stats as $stat) {
            if ($stat->getStatName() === $key) {
                return $stat->getStatModifier();
            }
        }

        return 0;
    }
}

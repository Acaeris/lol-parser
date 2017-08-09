<?php

namespace LeagueOfData\Entity\Rune;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Rune implements RuneInterface, ImmutableInterface
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
    private $runeName;

    /**
     * @var int
     */
    private $runeID;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $runeID,
        string $runeName,
        string $description,
        string $imageName,
        array $stats,
        array $tags,
        string $version,
        string $region
    ) {
        $this->constructImmutable();
        $this->runeID = $runeID;
        $this->runeName = $runeName;
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
            'rune_id' => $this->runeID,
            'version' => $this->version,
            'region' => $this->region
        ];
    }

    /**
     * Rune ID
     *
     * @return int
     */
    public function getRuneID() : int
    {
        return $this->runeID;
    }

    /**
     * Rune Name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->runeName;
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
     * @param  string $key
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

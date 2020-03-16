<?php

namespace App\Models\Masteries;

use App\Models\Image;
use App\Models\Immutable\ImmutableInterface;
use App\Models\Immutable\ImmutableTrait;

class Mastery implements MasteryInterface, ImmutableInterface
{
    /* @var int Mastery ID */
    private $masteryID;

    /* @var string Mastery name */
    private $masteryName;

    /* @var array Description */
    private $description;

    /* @var Image Icon */
    private $image;

    /* @var string Prerequisites */
    private $prereq;

    /* @var int Ranks */
    private $ranks;

    /* @var string Mastery tree */
    private $masteryTree;

    /* @var string Region */
    private $region;

    /* @var string Version */
    private $version;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $masteryID,
        string $masteryName,
        array $description,
        Image $image,
        string $prereq,
        int $ranks,
        string $masteryTree,
        string $region,
        string $version
    ) {
        $this->constructImmutable();
        $this->masteryID = $masteryID;
        $this->masteryName = $masteryName;
        $this->description = $description;
        $this->image = $image;
        $this->prereq = $prereq;
        $this->ranks = $ranks;
        $this->masteryTree = $masteryTree;
        $this->region = $region;
        $this->version = $version;
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
     * @return int Mastery ID
     */
    public function getMasteryID() : int
    {
        return $this->masteryID;
    }

    /**
     * @return string Mastery name
     */
    public function getMasteryName() : string
    {
        return $this->masteryName;
    }

    /**
     * @return array Description
     */
    public function getDescription() : array
    {
        return $this->description;
    }

    /**
     * @return Image Icon
     */
    public function getIcon() : Image
    {
        return $this->image;
    }

    /**
     * @return string Prerequisites
     */
    public function getPrerequisite() : string
    {
        return $this->prereq;
    }

    /**
     * @return int Ranks
     */
    public function getRanks() : int
    {
        return $this->ranks;
    }

    /**
     * @return string Mastery tree
     */
    public function getMasteryTree() : string
    {
        return $this->masteryTree;
    }

    /**
     * @return string Version
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * @return string Region
     */
    public function getRegion() : string
    {
        return $this->region;
    }
}

<?php
namespace LeagueOfData\Entity\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

/**
 * Champion Passive model
 *
 * @author caitlyn.osborne
 */
class ChampionPassive implements ChampionPassiveInterface, ImmutableInterface
{

    /**
     * @var int
     */
    private $championId;

    /**
     * @var string
     */
    private $passiveName;

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
    private $version;

    /**
     * @var string
     */
    private $region;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $championId,
        string $passiveName,
        string $imageName,
        string $description,
        string $version,
        string $region
    ) {
        $this->constructImmutable();

        $this->region = $region;
        $this->version = $version;
        $this->description = $description;
        $this->imageName = $imageName;
        $this->passiveName = $passiveName;
        $this->championId = $championId;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData() : array
    {
        return [
            'champion_id' => $this->championId,
            'passive_name' => $this->passiveName,
            'version' => $this->version,
            'region' => $this->region
        ];
    }

    /**
     * Get Champion ID
     *
     * @return int
     */
    public function getChampionID() : int
    {
        return $this->championId;
    }

    /**
     * Get Passive Name
     *
     * @return string
     */
    public function getPassiveName() : string
    {
        return $this->passiveName;
    }

    /**
     * Get Image Name
     *
     * @return string
     */
    public function getImageName() : string
    {
        return $this->imageName;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get Version
     *
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * Get Region
     *
     * @return string
     */
    public function getRegion() : string
    {
        return $this->region;
    }
}

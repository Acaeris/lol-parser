<?php

namespace App\Models\Champion;

use App\Models\Immutable\ImmutableInterface;
use App\Models\Immutable\ImmutableTrait;

/**
 * Champion Details
 *
 * @author caitlyn.osborne
 */
class ChampionStats implements ChampionStatsInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var int Champion ID
     */
    private $championId;

    /**
     * @var ChampionRegenResourceInterface Health details
     */
    private $health;

    /**
     * @var ChampionRegenResourceInterface Resource details (e.g. Mana, Rage, Energy)
     */
    private $resource;

    /**
     * @var ChampionAttackInterface Attack details
     */
    private $attack;

    /**
     * @var ChampionDefenseInterface Armor details
     */
    private $armor;

    /**
     * @var ChampionDefenseInterface Magic resistance details
     */
    private $magicResist;

    /**
     * @var float Movement Speed
     */
    private $moveSpeed;

    /**
     * @var string Version
     */
    private $version;

    /**
     * @var string Region
     */
    private $region;

    /**
     * Main Constructor
     *
     * @param int                            $champion_id
     * @param ChampionRegenResourceInterface $health
     * @param ChampionRegenResourceInterface $mana
     * @param ChampionAttackInterface        $attack
     * @param ChampionDefenseInterface       $armor
     * @param ChampionDefenseInterface       $magicResist
     * @param float                          $moveSpeed
     * @param string                         $version
     * @param string                         $region
     */
    public function __construct(
        int $champion_id,
        ChampionRegenResourceInterface $health,
        ChampionRegenResourceInterface $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist,
        float $moveSpeed,
        string $version,
        string $region
    ) {
        $this->constructImmutable();

        $this->health = $health;
        $this->resource = $mana;
        $this->attack = $attack;
        $this->armor = $armor;
        $this->magicResist = $magicResist;
        $this->moveSpeed = $moveSpeed;
        $this->championId = $champion_id;
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
            'champion_id' => $this->championId,
            'version' => $this->version,
            'region' => $this->region
        ];
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
     * Champion movement speed
     *
     * @return float
     */
    public function getMoveSpeed() : float
    {
        return round($this->moveSpeed, 2);
    }

    /**
     * Champion Health
     *
     * @return ChampionRegenResourceInterface
     */
    public function getHealth() : ChampionRegenResourceInterface
    {
        return $this->health;
    }

    /**
     * Champion Resource (e.g. Mana, Rage, Energy, etc.)
     *
     * @return ChampionRegenResourceInterface
     */
    public function getResource() : ChampionRegenResourceInterface
    {
        return $this->resource;
    }

    /**
     * Champion Attack
     *
     * @return ChampionAttackInterface
     */
    public function getAttack() : ChampionAttackInterface
    {
        return $this->attack;
    }

    /**
     * Champion Armor
     *
     * @return ChampionDefenseInterface
     */
    public function getArmor() : ChampionDefenseInterface
    {
        return $this->armor;
    }

    /**
     * Champion Magic Resist
     *
     * @return ChampionDefenseInterface
     */
    public function getMagicResist() : ChampionDefenseInterface
    {
        return $this->magicResist;
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

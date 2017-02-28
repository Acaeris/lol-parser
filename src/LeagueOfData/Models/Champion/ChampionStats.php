<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

/**
 * Champion Details
 *
 * @author caitlyn.osborne
 */
final class ChampionStats implements ChampionStatsInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

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
     * Factory Constructor
     *
     * @param array $champion
     * @return ChampionStatsInterface
     */
    public static function fromState(array $champion) : ChampionStatsInterface
    {
        $health = ChampionRegenResource::fromState(ChampionRegenResource::RESOURCE_HEALTH, $champion);
        $resource = ChampionRegenResource::fromState(ChampionRegenResource::RESOURCE_MANA, $champion);
        $attack = ChampionAttack::fromState($champion);
        $armor = ChampionDefense::fromState(ChampionDefense::DEFENSE_ARMOR, $champion);
        $magicResist = ChampionDefense::fromState(ChampionDefense::DEFENSE_MAGICRESIST, $champion);

        return new self($health, $resource, $attack, $armor, $magicResist, $champion['moveSpeed']);
    }

    /**
     * Main Constructor
     *
     * @param ChampionRegenResourceInterface $health
     * @param ChampionRegenResourceInterface $mana
     * @param ChampionAttackInterface $attack
     * @param ChampionDefenseInterface $armor
     * @param ChampionDefenseInterface $magicResist
     * @param float $moveSpeed
     */
    public function __construct(
        ChampionRegenResourceInterface $health,
        ChampionRegenResourceInterface $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist,
        float $moveSpeed
    ) {
        $this->constructImmutable();

        $this->health = $health;
        $this->resource = $mana;
        $this->attack = $attack;
        $this->armor = $armor;
        $this->magicResist = $magicResist;
        $this->moveSpeed = $moveSpeed;
    }

    /**
     * Champion movement speed
     *
     * @return float
     */
    public function moveSpeed() : float
    {
        return round($this->moveSpeed, 2);
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion resource data as an array
     */
    public function toArray() : array
    {
        return array_merge(['moveSpeed' => $this->moveSpeed], $this->health->toArray(), $this->resource->toArray(),
            $this->attack->toArray(), $this->armor->toArray(), $this->magicResist->toArray());
    }

    /**
     * Champion Health
     *
     * @return ChampionRegenResourceInterface
     */
    public function health() : ChampionRegenResourceInterface
    {
        return $this->health;
    }

    /**
     * Champion Resource (e.g. Mana, Rage, Energy, etc.)
     *
     * @return ChampionRegenResourceInterface
     */
    public function resource() : ChampionRegenResourceInterface
    {
        return $this->resource;
    }

    /**
     * Champion Attack
     *
     * @return ChampionAttackInterface
     */
    public function attack() : ChampionAttackInterface
    {
        return $this->attack;
    }

    /**
     * Champion Armor
     *
     * @return ChampionDefenseInterface
     */
    public function armor() : ChampionDefenseInterface
    {
        return $this->armor;
    }

    /**
     * Champion Magic Resist
     *
     * @return ChampionDefenseInterface
     */
    public function magicResist() : ChampionDefenseInterface
    {
        return $this->magicResist;
    }
}

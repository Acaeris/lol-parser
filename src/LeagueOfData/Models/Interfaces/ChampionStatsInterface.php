<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Stats Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionStats
 */
interface ChampionStatsInterface
{
    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion resource data as an array
     */
    public function toArray() : array;

    /**
     * Champion movement speed
     *
     * @return float
     */
    public function moveSpeed() : float;

    /**
     * Champion Health
     *
     * @return ChampionRegenResourceInterface
     */
    public function health() : ChampionRegenResourceInterface;

    /**
     * Champion Resource (e.g. Mana, Rage, Energy, etc.)
     *
     * @return ChampionRegenResourceInterface
     */
    public function resource() : ChampionRegenResourceInterface;

    /**
     * Champion Attack
     *
     * @return ChampionAttackInterface
     */
    public function attack() : ChampionAttackInterface;

    /**
     * Champion Armor
     *
     * @return ChampionDefenseInterface
     */
    public function armor() : ChampionDefenseInterface;

    /**
     * Champion Magic Resist
     *
     * @return ChampionDefenseInterface
     */
    public function magicResist() : ChampionDefenseInterface;
}

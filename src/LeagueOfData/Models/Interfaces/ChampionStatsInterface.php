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
     * Champion movement speed
     *
     * @return float
     */
    public function getMoveSpeed() : float;

    /**
     * Champion Health
     *
     * @return ChampionRegenResourceInterface
     */
    public function getHealth() : ChampionRegenResourceInterface;

    /**
     * Champion Resource (e.g. Mana, Rage, Energy, etc.)
     *
     * @return ChampionRegenResourceInterface
     */
    public function getResource() : ChampionRegenResourceInterface;

    /**
     * Champion Attack
     *
     * @return ChampionAttackInterface
     */
    public function getAttack() : ChampionAttackInterface;

    /**
     * Champion Armor
     *
     * @return ChampionDefenseInterface
     */
    public function getArmor() : ChampionDefenseInterface;

    /**
     * Champion Magic Resist
     *
     * @return ChampionDefenseInterface
     */
    public function getMagicResist() : ChampionDefenseInterface;
}

<?php

namespace App\Models\Champion;

use App\Models\EntityInterface;

/**
 * Champion Stats Interface
 *
 * @author caitlyn.osborne
 */
interface ChampionStatsInterface extends EntityInterface
{
    /**
     * Get champion ID
     */
    public function getChampionID() : int;

    /**
     * Champion movement speed
     */
    public function getMoveSpeed() : float;

    /**
     * Champion Health
     */
    public function getHealth() : ChampionRegenResourceInterface;

    /**
     * Champion Resource (e.g. Mana, Rage, Energy, etc.)
     */
    public function getResource() : ChampionRegenResourceInterface;

    /**
     * Champion Attack
     */
    public function getAttack() : ChampionAttackInterface;

    /**
     * Champion Armor
     */
    public function getArmor() : ChampionDefenseInterface;

    /**
     * Champion Magic Resist
     */
    public function getMagicResist() : ChampionDefenseInterface;

    /**
     * Champion version
     */
    public function getVersion() : string;

    /**
     * Get champion stat region
     */
    public function getRegion() : string;
}

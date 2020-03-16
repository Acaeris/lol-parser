<?php

namespace App\Models\Champions;

/**
 * Champion statistics
 */
class ChampionStats
{
    /**
     * Armor
     *
     * @var float
     */
    private $armor;

    /**
     * Armor per level
     *
     * @var float
     */
    private $armorPerLevel;

    /**
     * Health
     *
     * @var float
     */
    private $health;

    /**
     * Health per level
     *
     * @var float
     */
    private $healthPerLevel;

    /**
     * Health regen
     *
     * @var float
     */
    private $healthRegen;

    /**
     * Health regen per level
     *
     * @var float
     */
    private $healthRegenPerLevel;

    /**
     * Mana
     *
     * @var float
     */
    private $mana;

    /**
     * Mana per level
     *
     * @var float
     */
    private $manaPerLevel;

    /**
     * Mana Regen
     *
     * @var float
     */
    private $manaRegen;

    /**
     * Mana regen per level
     *
     * @var float
     */
    private $manaRegenPerLevel;

    /**
     * Attack damage
     *
     * @var float
     */
    private $attackDamage;

    /**
     * Attack damage per level
     *
     * @var float
     */
    private $attackDamagePerLevel;

    /**
     * Attack speed offset
     *
     * @var float
     */
    private $attackSpeedOffset;

    /**
     * Attack speed per level
     *
     * @var float
     */
    private $attackSpeedPerLevel;

    /**
     * Attack range
     *
     * @var float
     */
    private $attackRange;

    /**
     * Spell block
     *
     * @var float
     */
    private $spellBlock;

    /**
     * Spell block per level
     *
     * @var float
     */
    private $spellBlockPerLevel;

    /**
     * Move speed
     *
     * @var float
     */
    private $movespeed;

    /**
     * Crit
     *
     * @var float
     */
    private $crit;

    /**
     * Crit per level
     *
     * @var float
     */
    private $critPerLevel;

    /**
     * @param float $health - Health
     * @param float $healthPerLevel - Health per level
     * @param float $healthRegen - Health regen
     * @param float $healthRegenPerLevel - Health regen per level
     * @param float $mana - Mana
     * @param float $manaPerLevel - Mana per level
     * @param float $manaRegen - Mana regen
     * @param float $manaRegenPerLevel - Mana regen per level
     * @param float $attackDamage - Attack damage
     * @param float $attackDamagePerLevel - Attack damage per level
     * @param float $attackSpeedOffset - Attack speed offset
     * @param float $attackSpeedPerLevel - Attack speed per level
     * @param float $attackRange - Attack range
     * @param float $armor - Armor
     * @param float $armorPerLevel - Armor per level
     * @param float $spellBlock - Spell block
     * @param float $spellBlockPerLevel - Spell block per level
     * @param float $movespeed - Movement speed
     * @param float $crit - Critical strike chance
     * @param float $critPerLevel - Critical strike chance per level
     */
    public function __construct(
        float $health,
        float $healthPerLevel,
        float $healthRegen,
        float $healthRegenPerLevel,
        float $mana,
        float $manaPerLevel,
        float $manaRegen,
        float $manaRegenPerLevel,
        float $attackDamage,
        float $attackDamagePerLevel,
        float $attackSpeedOffset,
        float $attackSpeedPerLevel,
        float $attackRange,
        float $armor,
        float $armorPerLevel,
        float $spellBlock,
        float $spellBlockPerLevel,
        float $movespeed,
        float $crit,
        float $critPerLevel
    ) {
        $this->armor = $armor;
        $this->armorPerLevel = $armorPerLevel;
        $this->health = $health;
        $this->healthPerLevel = $healthPerLevel;
        $this->healthRegen = $healthRegen;
        $this->healthRegenPerLevel = $healthRegenPerLevel;
        $this->mana = $mana;
        $this->manaPerLevel = $manaPerLevel;
        $this->manaRegen = $manaRegen;
        $this->manaRegenPerLevel = $manaRegenPerLevel;
        $this->attackDamage = $attackDamage;
        $this->attackDamagePerLevel = $attackDamagePerLevel;
        $this->attackSpeedOffset = $attackSpeedOffset;
        $this->attackSpeedPerLevel = $attackSpeedPerLevel;
        $this->attackRange = $attackRange;
        $this->spellBlock = $spellBlock;
        $this->spellBlockPerLevel = $spellBlockPerLevel;
        $this->movespeed = $movespeed;
        $this->crit = $crit;
        $this->critPerLevel = $critPerLevel;
    }

    public function getArmor(): float
    {
        return $this->armor;
    }

    public function getArmorPerLevel(): float
    {
        return $this->armorPerLevel;
    }

    public function getHealth(): float
    {
        return $this->health;
    }

    public function getHealthPerLevel(): float
    {
        return $this->healthPerLevel;
    }

    public function getHealthRegen(): float
    {
        return $this->healthRegen;
    }

    public function getHealthRegenPerLevel(): float
    {
        return $this->healthRegenPerLevel;
    }

    public function getMana(): float
    {
        return $this->mana;
    }

    public function getManaPerLevel(): float
    {
        return $this->manaPerLevel;
    }

    public function getManaRegen(): float
    {
        return $this->manaRegen;
    }

    public function getManaRegenPerLevel(): float
    {
        return $this->manaRegenPerLevel;
    }

    public function getAttackDamage(): float
    {
        return $this->attackDamage;
    }

    public function getAttackDamagePerLevel(): float
    {
        return $this->attackDamagePerLevel;
    }

    public function getAttackSpeedOffset(): float
    {
        return $this->attackSpeedOffset;
    }

    public function getAttackSpeedPerLevel(): float
    {
        return $this->attackSpeedPerLevel;
    }

    public function getAttackRange(): float
    {
        return $this->attackRange;
    }

    public function getSpellBlock(): float
    {
        return $this->spellBlock;
    }

    public function getSpellBlockPerLevel(): float
    {
        return $this->spellBlockPerLevel;
    }

    public function getMovespeed(): float
    {
        return $this->movespeed;
    }

    public function getCrit(): float
    {
        return $this->crit;
    }

    public function getCritPerLevel(): float
    {
        return $this->critPerLevel;
    }
}

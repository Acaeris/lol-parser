<?php

namespace App\Models\Champions;

use App\Models\Image;
use App\Models\Spells\SpellLevelTip;
use App\Models\Spells\SpellVar;

class ChampionSpell
{
    /**
     * Spell key
     *
     * @var string
     */
    private $key;

    /**
     * Spell name
     *
     * @var string
     */
    private $spellName;

    /**
     * Spell description
     *
     * @var string
     */
    private $description;

    /**
     * Sanitised spell description
     *
     * @var string
     */
    private $sanitisedDescription;

    /**
     * Tooltip
     *
     * @var string
     */
    private $tooltip;

    /**
     * Sanitised tooltip
     *
     * @var string
     */
    private $sanitisedTooltip;

    /**
     * Spell resource
     *
     * @var string
     */
    private $resource;

    /**
     * Costs per level
     *
     * @var int[]
     */
    private $costs;

    /**
     * Cost type
     *
     * @var string
     */
    private $costType;

    /**
     * Cooldowns
     *
     * @var int[]
     */
    private $cooldowns;

    /**
     * Image
     *
     * @var Image
     */
    private $image;

    /**
     * Alternative images
     *
     * @var Image[]
     */
    private $altImages;

    /**
     * Spell level tip
     *
     * @var SpellLevelTip
     */
    private $spellLevelTip;

    /**
     * Spell vars
     *
     * @var SpellVar[]
     */
    private $spellVars;

    /**
     * Effects
     *
     * @var array[]
     */
    private $effects;

    /**
     * Ranges
     *
     * @var array
     */
    private $ranges;

    /**
     * Max Rank
     *
     * @var int
     */
    private $maxRank;

    /**
     * @param string $key - Spell key
     * @param string $spellName - Spell Name
     * @param string $description - Description
     * @param string $sanitisedDescription - Description without tags
     * @param string $tooltip - Tooltip
     * @param string $sanitisedTooltip - Tooltip without tags
     * @param string $resource - Resource text for spell
     * @param float[] $costs - Resource costs per rank
     * @param string $costType - Resource Type used
     * @param int[] $cooldowns - Cooldown per rank
     * @param Image $image - Spell icon
     * @param Image[] $altImages - Alternative icons
     * @param SpellLevelTip $spellLevelTip - Level tooltip
     * @param SpellVar[] $spellVars - Spell variables
     * @param array[] $effects - Effects collection
     * @param int[] $ranges - Range per rank
     * @param int $maxRank - Max rank of the spell
     */
    public function __construct(
        string $key,
        string $spellName,
        string $description,
        string $sanitisedDescription,
        string $tooltip,
        string $sanitisedTooltip,
        string $resource,
        array $costs,
        string $costType,
        array $cooldowns,
        Image $image,
        array $altImages,
        SpellLevelTip $spellLevelTip,
        array $spellVars,
        array $effects,
        array $ranges,
        int $maxRank
    ) {
        $this->key = $key;
        $this->spellName = $spellName;
        $this->description = $description;
        $this->sanitisedDescription = $sanitisedDescription;
        $this->tooltip = $tooltip;
        $this->sanitisedTooltip = $sanitisedTooltip;
        $this->resource = $resource;
        $this->costs = $costs;
        $this->costType = $costType;
        $this->cooldowns = $cooldowns;
        $this->image = $image;
        $this->altImages = $altImages;
        $this->spellLevelTip = $spellLevelTip;
        $this->spellVars = $spellVars;
        $this->effects = $effects;
        $this->ranges = $ranges;
        $this->maxRank = $maxRank;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->spellName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSanitisedDescription(): string
    {
        return $this->sanitisedDescription;
    }

    public function getTooltip(): string
    {
        return $this->tooltip;
    }

    public function getSanitisedTooltip(): string
    {
        return $this->sanitisedTooltip;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getCosts(): array
    {
        return $this->costs;
    }

    public function getCostBurn(): string
    {
        return implode('/', $this->costs);
    }

    public function getCostType(): string
    {
        return $this->costType;
    }

    public function getCooldowns(): array
    {
        return $this->cooldowns;
    }

    public function getCooldownBurn(): string
    {
        return implode('/', $this->cooldowns);
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function getAltImages(): array
    {
        return $this->altImages;
    }

    public function getSpellLevelTip(): SpellLevelTip
    {
        return $this->spellLevelTip;
    }

    public function getSpellVars(): array
    {
        return $this->spellVars;
    }

    public function getEffects(): array
    {
        return $this->effects;
    }

    public function getEffectBurn(): array
    {
        $output = [];

        foreach ($this->effects as $effect) {
            $output[] = (is_array($effect)) ? implode('/', $effect) : "";
        }

        return $output;
    }

    public function getRanges(): array
    {
        return $this->ranges;
    }

    public function getRangeBurn(): string
    {
        return implode('/', $this->ranges);
    }

    public function getMaxRank(): int
    {
        return $this->maxRank;
    }
}

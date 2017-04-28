<?php

namespace LeagueOfData\Models;

/**
 * The trait contains the core implementation of all resources
 *
 * The trait implements the following:
 *
 * * The resource type
 * * Resource base value
 * * Resource increase rate
 *
 * @author caitlyn.osborne
 */
trait ResourceTrait
{
    private $validResources = [
        'hp',
        'mp',
        'mana',
        'rage',
        'gnarfury',
        'heat',
        'energy',
        'wind',
        'flow',
        'fury',
        'dragonfury',
        'battlefury',
        'blood well',
        'bloodwell',
        'crimson rush',
        'ferocity',
        'courage',
        'shield',
        'none',
    ];

    /** @var string The type of resource this object represents */
    private $type;
    /** @var float The base amount of that resource the champion starts with */
    private $baseValue;
    /** @var float The amount the maximum resource increases per level */
    private $perLevel;

    /**
     * Create the resource
     *
     * @param float $baseValue
     * @param float $perLevel
     */
    public function constructResource(float $baseValue, float $perLevel)
    {
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
    }

    /**
     * Validate the type of resource
     *
     * @param string $type
     *
     * @return bool
     */
    public function isValid(string $type)
    {
        if (in_array($type, $this->validResources)) {
            return true;
        }
        return false;
    }

    /**
     * Base resource value
     *
     * @return float Base resource value
     */
    public function getBaseValue() : float
    {
        return round($this->baseValue, 2);
    }

    /**
     * Base resource increase per level
     *
     * @return float Base resource increase per level
     */
    public function getIncreasePerLevel() : float
    {
        return round($this->perLevel, 2);
    }

    /**
     * Calculate the max resource value at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of max resource at the given level
     */
    public function calculateValueAtLevel(int $level) : float
    {
        return round($this->baseValue + $this->perLevel * ($level - 1), 2);
    }
}

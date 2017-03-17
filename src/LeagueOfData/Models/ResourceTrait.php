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
        [
            'shortCode' => 'hp',
            'fullText' => 'Health',
            'color' => 'red'
        ],
        [
            'shortCode' => 'armor',
            'fullText' => 'Armor',
            'color' => 'grey'
        ],
        [
            'shortCode' => 'mp',
            'fullText' => 'Mana',
            'color' => 'blue',
        ],
        [
            'shortCode' => 'rage',
            'fullText' => 'Rage',
            'color' => 'orange'
        ],
        [
            'shortCode' => 'spellBlock',
            'fullText' => 'Magic Resistance',
            'color' => 'light blue'
        ]
    ];

    /** @var string The type of resource this object represents */
    private $type;
    /** @var float The base amount of that resource the champion starts with */
    private $baseValue;
    /** @var float The amount the maximum resource increases per level */
    private $perLevel;

    public function constructResource(string $type, float $baseValue, float $perLevel)
    {
        $this->validateType($type);

        $this->type = $type;
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
    }

    private function validateType(string $type)
    {
        $found = false;
        foreach ($this->validResources as $resourceType) {
            if ($resourceType['shortCode'] === $type || $resourceType['fullText'] === $type) {
                $found = true;
            }
        }
        if (!$found) {
            throw new \InvalidArgumentException("Invalid resource type supplied: ".$type);
        }
    }

    /**
     * Resource Type
     *
     * @return string Resource type
     */
    public function type() : string
    {
        return $this->type;
    }

    /**
     * Base resource value
     *
     * @return float Base resource value
     */
    public function baseValue() : float
    {
        return round($this->baseValue, 2);
    }

    /**
     * Base resource increase per level
     *
     * @return float Base resource increase per level
     */
    public function increasePerLevel() : float
    {
        return round($this->perLevel, 2);
    }

    /**
     * Calculate the max resource value at a given level
     *
     * @param int $level Level of the champion
     * @return float Value of max resource at the given level
     */
    public function valueAtLevel(int $level) : float
    {
        return round($this->baseValue + $this->perLevel * ($level - 1), 2);
    }
}

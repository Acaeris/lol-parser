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
        ]
    ];

    /** @var string The type of resource this object represents */
    private $type;
    /** @var int The base amount of that resource the champion starts with */
    private $baseValue;
    /** @var int The amount the maximum resource increases per level */
    private $perLevel;

    public function constructResource(string $type, int $baseValue, int $perLevel)
    {
        $this->validateType($type);

        $this->type = $type;
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
    }

    private function validateType(string $type) {
        $found = false;
        foreach ($this->validResources as $resourceType) {
            if ($resourceType['shortCode'] === $type || $resourceType['fullText'] === $type) {
                $found = true;
            }
        }
        if (!$found) {
            throw new \InvalidArgumentException("Invalid resource type supplied: " . $type);
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
     * @return int Base resource value
     */
    public function baseValue() : int
    {
        return $this->baseValue;
    }

    /**
     * Base resource increase per level
     * 
     * @return int Base resource increase per level
     */
    public function increasePerLevel() : int
    {
        return $this->perLevel;
    }

    /**
     * Calculate the max resource value at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of max resource at the given level
     */
    public function valueAtLevel(int $level) : int
    {
        return $this->baseValue + $this->perLevel * $level;
    }
}

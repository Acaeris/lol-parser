<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

/**
 * Champion Resources.
 * Used to represent various similar champion resources, including:
 * - Health ('hp')
 * - Mana ('mp')
 * - Rage ('rage')
 * - Energy ('energy')
 * - Wind ('wind')
 * - Ammo ('ammo')
 * 
 * @author caitlyn.osborne
 */
final class ChampionResource implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /** @var string Tag for Resource Type: Health */
    const RESOURCE_HEALTH = 'hp';
    /** @var string Tag for Resource Type: Mana */
    const RESOURCE_MANA = 'mp';

    /** @var string The type of resource this object represents */
    private $type;
    /** @var int The base amount of that resource the champion starts with */
    private $baseValue;
    /** @var int The amount the maximum resource increases per level */
    private $perLevel;
    /** @var int The base regeneration rate of this resource */
    private $regen;
    /** @var int The amount the regeneration rate increases per level */
    private $regenPerLevel;

    /**
     * Creates a new Champion Resource from an existing state.
     * Used as an alternative constructor as PHP does not support mutliple constructors.
     * 
     * @param string $type Type of resource represented by this object
     * @param array $champion Data from an existing state (e.g. SQL result, Json or object converted to array)
     * @return \LeagueOfData\Models\ChampionResource Resultant Champion Resource
     */
    public static function fromState(string $type, array $champion) : ChampionResource
    {
        return new self(
            $type,
            $champion[$type],
            $champion[$type . 'PerLevel'],
            $champion[$type . 'Regen'],
            $champion[$type . 'RegenPerLevel']
        );
    }

    /**
     * Construct a Champion Resource object
     * 
     * @param string $type Type of resource represented by this object
     * @param int $baseValue The base amount of that resource the champion starts with 
     * @param int $perLevel The amount the maximum resource increases per level
     * @param int $regen The base regeneration rate of this resource
     * @param int $regenPerLevel The amount the regeneration rate increases per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, int $baseValue, int $perLevel, int $regen, int $regenPerLevel)
    {
        $this->constructImmutable();

        $this->type = $type;
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Champion resource data as an array
     */
    public function toArray() : array
    {
        return [
            $this->type => $this->baseValue,
            $this->type . 'PerLevel' => $this->perLevel,
            $this->type . 'Regen' => $this->regen,
            $this->type . 'RegenPerLevel' => $this->regenPerLevel
        ];
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

    /**
     * Base regeneration rate
     * 
     * @return int Base regeneration rate
     */
    public function regenBaseValue() : int
    {
        return $this->regen;
    }

    /**
     * Regeneration rate increase per level
     * 
     * @return int Regeneration rate increase per level
     */
    public function regenIncreasePerLevel() : int
    {
        return $this->regenPerLevel;
    }

    /**
     * Calculate the regeneration rate at given level
     * 
     * @param int $level Level of the champion
     * @return int Value of regeneration rate at the given level
     */
    public function regenAtLevel(int $level) : int
    {
        return $this->regen + $this->regenPerLevel * $level;
    }
}
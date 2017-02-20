<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\ResourceTrait;
use LeagueOfData\Models\Interfaces\ChampionResourceInterface;
use LeagueOfData\Models\Interfaces\ResourceInterface;

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
final class ChampionResource implements ChampionResourceInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /** @var string Tag for Resource Type: Health */
    const RESOURCE_HEALTH = 'hp';
    /** @var string Tag for Resource Type: Mana */
    const RESOURCE_MANA = 'mp';

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
     * @return ChampionResourceInterface Resultant Champion Resource
     */
    public static function fromState(string $type, array $champion) : ChampionResourceInterface
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
        $this->constructResource($type, $baseValue, $perLevel);

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
<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\SpellResourceInterface;
use LeagueOfData\Models\Interfaces\ResourceInterface;

/**
 * Spell Resources.
 * Used to represent various similar spell resources, including:
 * - Health ('hp')
 * - Mana ('mp')
 * - Rage ('rage')
 * - Energy ('energy')
 * - Wind ('wind')
 * - Ammo ('ammo')
 * 
 * @author caitlyn.osborne
 */
final class SpellResource implements SpellResourceInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;
    
    /**
     * Creates a new Spell Resource from an existing state.
     * Used as an alternative constructor as PHP does not support mutliple constructors.
     * 
     * @param string $type Type of resource represented by this object
     * @param array $spell Data from an existing state (e.g. SQL result, Json or object converted to array)
     * @return SpellResourceInterface Resultant Spell Resource
     */
    public static function fromState(string $type, array $spell) : SpellResourceInterface
    {
        return new self(
            $type,
            $spell[$type],
            $spell[$type . 'PerLevel']
        );
    }

    /**
     * Construct a Spell Resource object
     * 
     * @param string $type Type of resource represented by this object
     * @param int $baseValue The base amount of that resource the spell requires to case
     * @param int $perLevel The amount the resource cost increases per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, int $baseValue, int $perLevel)
    {
        $this->constructImmutable();
        $this->constructResource($type, $baseValue, $perLevel);
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     * 
     * @return array Spell resource data as an array
     */
    public function toArray() : array
    {
        return [
            $this->type => $this->baseValue,
            $this->type . 'PerLevel' => $this->perLevel
        ];
    }
}

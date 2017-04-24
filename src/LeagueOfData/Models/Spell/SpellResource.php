<?php

namespace LeagueOfData\Models\Spell;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ResourceInterface;
use LeagueOfData\Models\ResourceTrait;

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
final class SpellResource implements ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /**
     * Construct a Spell Resource object
     *
     * @param string $type      Type of resource represented by this object
     * @param int    $baseValue The base amount of that resource the spell requires to case
     * @param int    $perLevel  The amount the resource cost increases per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, int $baseValue, int $perLevel)
    {
        $this->constructImmutable();
        $this->constructResource($type, $baseValue, $perLevel);
    }
}

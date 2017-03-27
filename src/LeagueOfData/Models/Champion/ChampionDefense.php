<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\ResourceTrait;
use LeagueOfData\Models\Interfaces\ResourceInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;

/**
 * Champion Defense.
 * Used to represent the defences of the champion.
 * - Armor ('armor')
 * - Magic Resistance ('spellBlock')
 *
 * @author caitlyn.osborne
 */
final class ChampionDefense implements ChampionDefenseInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /** @var string Tag for defence type: Armor */
    const DEFENSE_ARMOR = 'armor';
    /** @var string Tag for defence type: Magic Resistance */
    const DEFENSE_MAGICRESIST = 'spellBlock';

    /**
     * Construct a Champion Defense object
     *
     * @param string $type     Type of defence represented by this object
     * @param float  $base     The base amount of defence the champion starts with
     * @param float  $perLevel The amount of defence the champion gains per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, float $base, float $perLevel)
    {
        $this->constructImmutable();
        $this->constructResource($type, $base, $perLevel);
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion defence data as an array
     */
    public function toArray() : array
    {
        return [
            $this->type => $this->baseValue,
            $this->type.'PerLevel' => $this->perLevel
        ];
    }
}

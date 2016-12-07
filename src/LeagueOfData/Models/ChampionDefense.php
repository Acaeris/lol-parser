<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

/**
 * Champion Defense.
 * Used to represent the defences of the champion.
 * - Armor ('armor')
 * - Magic Resistance ('spellBlock')
 * 
 * @author caitlyn.osborne
 */
final class ChampionDefense implements ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /** @var string Tag for defence type: Armor */
    const DEFENSE_ARMOR = 'armor';
    /** @var string Tag for defence type: Magic Resistance */
    const DEFENSE_MAGICRESIST = 'spellBlock';

    /** @var string The type of defence this object represents */
    private $type;
    /** @var int The base amount of defence the champion starts with */
    private $baseValue;
    /** @var int The amount of defence the champion gains per level */
    private $perLevel;

    /**
     * Creates a new Champion Defence from an existing state.
     * Use as an alternative constructor as PHP does not support multiple constructors.
     * 
     * @param string $type Type of defence represented by this object
     * @param array $champion Data from an existing state (e.g. SQL result, Json, or object converted to array)
     * @return \LeagueOfData\Models\ChampionDefense Resultant Champion Defense
     */
    public static function fromState(string $type, array $champion) : ChampionDefense
    {
        return new self(
            $type,
            $champion[$type],
            $champion[$type . 'PerLevel']
        );
    }

    /**
     * Construct a Champion Defense object
     * 
     * @param string $type Type of defence represented by this object
     * @param int $base The base amount of defence the champion starts with
     * @param int $perLevel The amount of defence the champion gains per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, int $base, int $perLevel)
    {
        $this->constructImmutable();

        $this->type = $type;
        $this->baseValue = $base;
        $this->perLevel = $perLevel;
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
            $this->type . 'PerLevel' => $this->perLevel
        ];
    }

    /**
     * Defence type
     * 
     * @return string Defence type
     */
    public function type() : string
    {
        return $this->type;
    }

    /**
     * Base defence value
     * 
     * @return int Base defence value
     */
    public function baseValue() : int
    {
        return $this->baseValue;
    }

    /**
     * Base defence increase per level
     * 
     * @return int Base defence increase per level
     */
    public function increasePerLevel() : int
    {
        return $this->perLevel;
    }

    /**
     * Calculate the amount of defence at a given level
     * 
     * @param int $level Level of the champion
     * @return int Value of defence at the given level
     */
    public function valueAtLevel(int $level) : int
    {
        return $this->baseValue + $this->perLevel * $level;
    }
}

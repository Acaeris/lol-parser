<?php

namespace LeagueOfData\Models\Spell;

use LeagueOfData\Models\Interfaces\SpellInterface;
use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class Spell implements SpellInterface, ImmutableInterface
{

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /** @var int Spell ID */
    private $spellID;
    /** @var string Spell Name */
    private $name;
    /** @var string Default key binding */
    private $keyBinding;
    /** @var string Spell icon file name */
    private $fileName;
    /** @var string Spell description */
    private $description;
    /** @var string Tooltip text */
    private $tooltip;
    /** @var string Resource Type */
    private $resourceType;
    /** @var array Spell Cooldowns */
    private $cooldowns;
    /** @var array Spell Ranges */
    private $ranges;
    /** @var array Spell costs */
    private $costs;
    /** @var int Maximum rank of the spell */
    private $maxRank;

    public function __construct(
        int $spellID,
        string $name,
        string $keyBinding,
        string $fileName,
        string $description,
        string $tooltip,
        string $resourceType,
        int $maxRank,
        array $cooldowns,
        array $ranges,
        array $costs
    ) {
    
        $this->constructImmutable();

        $this->spellID = $spellID;
        $this->name = $name;
        $this->keyBinding = $keyBinding;
        $this->fileName = $fileName;
        $this->description = $description;
        $this->tooltip = $tooltip;
        $this->resourceType = $resourceType;
        $this->maxRank = $maxRank;
        $this->cooldowns = $cooldowns;
        $this->ranges = $ranges;
        $this->costs = $costs;
    }

    /**
     * Spell ID
     *
     * @return int Internal ID of the spell. Used to link iterations of a spell.
     */
    public function getID() : int
    {
        return $this->spellID;
    }

    /**
     * Spell Name
     *
     * @return string Name of the spell
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * Default spell key binding
     *
     * @return string
     */
    public function keyBinding() : string
    {
        return $this->keyBinding;
    }

    /**
     * Spell icon file name
     *
     * @return string
     */
    public function fileName() : string
    {
        return $this->fileName;
    }

    /**
     * Spell description
     *
     * @return string Spell description
     */
    public function description() : string
    {
        return $this->description;
    }

    /**
     * Tooltip text
     *
     * @return string
     */
    public function tooltip() : string
    {
        return $this->tooltip;
    }

    /**
     * Resource Type
     *
     * @return string
     */
    public function resourceType() : string
    {
        return $this->resourceType;
    }

    /**
     * Max rank of the spell
     *
     * @return int Max Rank
     */
    public function maxRank() : int
    {
        return $this->maxRank;
    }

    /**
     * Spell cooldowns
     *
     * @return array List of cooldowns ordered by rank
     */
    public function cooldowns() : array
    {
        return $this->cooldowns;
    }

    /**
     * Spell Cooldown by rank
     *
     * @param int $rank
     * @return int Spell cooldown
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function cooldownByRank(int $rank) : int
    {
        if ($rank > $this->maxRank) {
            throw new \InvalidArgumentException('Requested rank beyond the maximum for this spell');
        }
        return $this->cooldowns[$rank - 1];
    }

    /**
     * Spells Ranges
     *
     * @return array
     */
    public function ranges() : array
    {
        return $this->ranges;
    }

    /**
     * Range by Rank
     *
     * @param int $rank
     *
     * @return int
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function rangeByRank(int $rank) : int
    {
        if ($rank > $this->maxRank) {
            throw new \InvalidArgumentException('Requested rank beyond the maximum for this spell');
        }
        return $this->ranges[$rank - 1];
    }

    /**
     * Spell costs
     *
     * @return array Spell costs
     */
    public function costs() : array
    {
        return $this->costs;
    }

    /**
     * Cost by spell rank
     *
     * @param int $rank
     * @return int Spell cost
     * @throws InvalidArgumentException if supplied rank is out of bounds for the spell.
     */
    public function costByRank(int $rank) : int
    {
        if ($rank > $this->maxRank) {
            throw new \InvalidArgumentException('Requested rank beyond the maximum for this spell');
        }
        return $this->costs[$rank - 1];
    }
}

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

    /**
     * @var int Spell ID
     */
    private $id;

    /**
     * @var string Spell Name
     */
    private $name;

    /**
     * @var string Spell description
     */
    private $description;

    /**
     * @var array Spell Cooldowns
     */
    private $cooldowns;

    /**
     * @var array Spell costs
     */
    private $costs;

    /**
     * @var int Maximum rank of the spell
     */
    private $maxRank;

    function __construct(int $id, string $name, string $description,
        int $maxRank, array $cooldowns, array $costs)
    {
        $this->constructImmutable();

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->maxRank = $maxRank;
        $this->cooldowns = $cooldowns;
        $this->costs = $costs;
    }

    /**
     * Spell ID
     *
     * @return int Internal ID of the spell. Used to link iterations of a spell.
     */
    public function id() : int
    {
        return $this->id;
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
     * Spell description
     *
     * @return string Spell description
     */
    public function description() : string
    {
        return $this->description;
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
            throw new \InvalidArgumentException('Rank unavailable');
        }
        return $this->cooldowns[$rank - 1];
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
            throw new \InvalidArgumentException('Rank unavalable');
        }
        return $this->costs[$rank - 1];
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion data as an array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'maxRank' => $this->maxRank,
            'cooldowns' => implode("|", $this->cooldowns),
            'costs' => implode("|", $this->costs)
        ];
    }
}

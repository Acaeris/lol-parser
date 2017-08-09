<?php

namespace LeagueOfData\Entity\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class ChampionSpellResource implements ChampionSpellResourceInterface, ImmutableInterface
{

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $name;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(string $name, array $values)
    {
        $this->constructImmutable();
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * Spell variable resource name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Spell variable resource values
     *
     * @return array
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Value by rank
     *
     * @param  int $rank
     * @return int
     */
    public function getValueByRank(int $rank) : int
    {
        if (!isset($this->values[$rank - 1])) {
            throw new \InvalidArgumentException('Rank too high for spell.');
        }
        return $this->values[$rank - 1];
    }
}

<?php

namespace LeagueOfData\Entity;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

class Stat implements StatInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var float
     */
    private $statMod;

    /**
     * @var string
     */
    private $statName;

    public function __construct(string $statName, float $statMod)
    {
        $this->constructImmutable();
        $this->statName = $statName;
        $this->statMod = $statMod;
    }

    /**
     * Stat Name
     *
     * @return string
     */
    public function getStatName() : string
    {
        return $this->statName;
    }

    /**
     * Stat Modifier
     *
     * @return float
     */
    public function getStatModifier() : float
    {
        return $this->statMod;
    }
}

<?php

namespace App\Models;

use App\Models\Immutable\ImmutableInterface;
use App\Models\Immutable\ImmutableTrait;

class Stat implements StatInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /* @var float Stat modifier */
    private $statMod;

    /* @var string Stat name */
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

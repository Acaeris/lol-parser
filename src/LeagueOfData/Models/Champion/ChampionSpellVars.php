<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Models\Interfaces\ChampionSpellVarsInterface;
use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

/**
 * Champion Spell variables
 * Used for templating variables in spell tooltips
 *
 * @author caitlyn.osborne
 */
final class ChampionSpellVars implements ChampionSpellVarsInterface, ImmutableInterface
{

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $coeff;

    /**
     * @var string
     */
    private $link;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(string $link, array $coeff, string $key)
    {
        $this->constructImmutable();

        $this->link = $link;
        $this->coeff = $coeff;
        $this->key = $key;
    }

    /**
     * Spell variable stat link
     *
     * @return string
     */
    public function getLink() : string
    {
        return $this->link;
    }

    /**
     * Spell variable coeff array
     *
     * @return array
     */
    public function getCoeff() : array
    {
        return $this->coeff;
    }

    /**
     * Spell variable key
     *
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * Get the coeff value by rank
     *
     * @param int $rank
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getCoeffByRank(int $rank) : float
    {
        if (!isset($this->coeff[$rank - 1])) {
            throw new \InvalidArgumentException('Rank too high for spell.');
        }
        return $this->coeff[$rank - 1];
    }
}

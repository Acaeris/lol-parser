<?php

namespace App\Models\Spells;

class SpellVar
{
    /**
     * Key
     *
     * @var string
     */
    private $key;

    /**
     * Ranks with
     *
     * @var string
     */
    private $ranksWith;

    /**
     * Dyn
     *
     * @var string
     */
    private $dyn;

    /**
     * Link
     *
     * @var string
     */
    private $link;

    /**
     * Coeff
     *
     * @var int[]
     */
    private $coeff;

    /**
     * @param string $key - Variable key
     * @param string $ranksWith - Ranks with ??
     * @param string $dyn - ??
     * @param string $link - Variable linked to...
     * @param float[] $coeff - Values for the variable
     */
    public function __construct(
        string $key,
        string $ranksWith,
        string $dyn,
        string $link,
        array $coeff
    ) {
        $this->key = $key;
        $this->ranksWith = $ranksWith;
        $this->dyn = $dyn;
        $this->link = $link;
        $this->coeff = $coeff;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getRanksWith(): string
    {
        return $this->ranksWith;
    }

    public function getDyn(): string
    {
        return $this->dyn;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getCoeff(): array
    {
        return $this->coeff;
    }
}

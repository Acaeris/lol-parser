<?php

namespace App\Models\Spells;

class SpellLevelTip
{
    /**
     * Labels
     *
     * @var string[]
     */
    private $labels;

    /**
     * Effects
     *
     * @var string[]
     */
    private $effects;

    /**
     * @param string[] $labels - Labels
     * @param string[] $effects - Effects
     */
    public function __construct(array $labels, array $effects)
    {
        $this->labels = $labels;
        $this->effects = $effects;
    }

    /**
     * @return string[] - Labels
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return string[] - Effects
     */
    public function getEffects(): array
    {
        return $this->effects;
    }
}

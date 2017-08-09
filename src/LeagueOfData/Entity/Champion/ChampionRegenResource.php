<?php

namespace LeagueOfData\Entity\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Entity\ResourceTrait;
use LeagueOfData\Entity\ResourceInterface;

/**
 * Champion Resources.
 * Used to represent various similar champion resources, including:
 * - Health ('hp')
 * - Mana ('mp')
 * - Rage ('rage')
 * - Energy ('energy')
 * - Wind ('wind')
 *
 * @package LeagueOfData\Entity\Champion
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class ChampionRegenResource implements ChampionRegenResourceInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /**
     * @var int The base regeneration rate of this resource
     */
    private $regen;

    /**
     * @var int The amount the regeneration rate increases per level
     */
    private $regenPerLevel;

    /**
     * Construct a Champion Resource object
     *
     * @param float $baseValue     The base amount of that resource the champion starts with
     * @param float $perLevel      The amount the maximum resource increases per level
     * @param float $regen         The base regeneration rate of this resource
     * @param float $regenPerLevel The amount the regeneration rate increases per level
     * @todo  Add validation of parameters.
     */
    public function __construct(float $baseValue, float $perLevel, float $regen, float $regenPerLevel)
    {
        $this->constructImmutable();
        $this->constructResource($baseValue, $perLevel);

        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    /**
     * Base regeneration rate
     *
     * @return float Base regeneration rate
     */
    public function getRegenBaseValue() : float
    {
        return round($this->regen, 3);
    }

    /**
     * Regeneration rate increase per level
     *
     * @return float Regeneration rate increase per level
     */
    public function getRegenIncreasePerLevel() : float
    {
        return round($this->regenPerLevel, 3);
    }

    /**
     * Calculate the regeneration rate at given level
     *
     * @param  int $level Level of the champion
     * @return float Value of regeneration rate at the given level
     */
    public function calculateRegenAtLevel(int $level) : float
    {
        return round($this->regen + $this->regenPerLevel * ($level - 1), 3);
    }
}

<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\ResourceTrait;
use LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface;
use LeagueOfData\Models\Interfaces\ResourceInterface;

/**
 * Champion Resources.
 * Used to represent various similar champion resources, including:
 * - Health ('hp')
 * - Mana ('mp')
 * - Rage ('rage')
 * - Energy ('energy')
 * - Wind ('wind')
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class ChampionRegenResource implements ChampionRegenResourceInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /** @var string Tag for Resource Type: Health */
    const RESOURCE_HEALTH = 'hp';
    /** @var string Tag for Resource Type: Mana */
    const RESOURCE_MANA = 'mp';

    /** @var int The base regeneration rate of this resource */
    private $regen;
    /** @var int The amount the regeneration rate increases per level */
    private $regenPerLevel;

    /**
     * Construct a Champion Resource object
     *
     * @param string $type          Type of resource represented by this object
     * @param float  $baseValue     The base amount of that resource the champion starts with
     * @param float  $perLevel      The amount the maximum resource increases per level
     * @param float  $regen         The base regeneration rate of this resource
     * @param float  $regenPerLevel The amount the regeneration rate increases per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, float $baseValue, float $perLevel, float $regen, float $regenPerLevel)
    {
        $this->constructImmutable();
        $this->constructResource($type, $baseValue, $perLevel);

        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    /**
     * Base regeneration rate
     *
     * @return float Base regeneration rate
     */
    public function regenBaseValue() : float
    {
        return round($this->regen, 3);
    }

    /**
     * Regeneration rate increase per level
     *
     * @return float Regeneration rate increase per level
     */
    public function regenIncreasePerLevel() : float
    {
        return round($this->regenPerLevel, 3);
    }

    /**
     * Calculate the regeneration rate at given level
     *
     * @param int $level Level of the champion
     * @return float Value of regeneration rate at the given level
     */
    public function regenAtLevel(int $level) : float
    {
        return round($this->regen + $this->regenPerLevel * ($level - 1), 3);
    }
}

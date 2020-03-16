<?php

namespace App\Models\Champion;

use App\Models\Immutable\ImmutableInterface;
use App\Models\Immutable\ImmutableTrait;
use App\Models\ResourceTrait;
use App\Models\ResourceInterface;

/**
 * Champion Defense.
 * Used to represent the defences of the champion.
 * - Armor ('armor')
 * - Magic Resistance ('spellBlock')
 *
 * @author caitlyn.osborne
 */
final class ChampionDefense implements ChampionDefenseInterface, ResourceInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }
    use ResourceTrait;

    /** @var string Tag for defence type: Armor */
    const DEFENSE_ARMOR = 'armor';
    /** @var string Tag for defence type: Magic Resistance */
    const DEFENSE_MAGICRESIST = 'spellBlock';

    /** @var string Defense type **/
    private $type;

    /**
     * Construct a Champion Defense object
     *
     * @param string $type     Type of defence represented by this object
     * @param float  $base     The base amount of defence the champion starts with
     * @param float  $perLevel The amount of defence the champion gains per level
     * @todo Add validation of parameters.
     */
    public function __construct(string $type, float $base, float $perLevel)
    {
        $this->constructImmutable();
        if ($this->isValidType($type)) {
            $this->type = $type;
        }
        $this->constructResource($base, $perLevel);
    }
    /**
     * Defence type
     *
     * @return string Defence type
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Check to make sure we are building a valid defense resource.
     *
     * @param string $type
     * @return boolean
     * @throws \InvalidArgumentException
     */
    private function isValidType(string $type) : bool
    {
        if ($type !==self::DEFENSE_ARMOR && $type !== self::DEFENSE_MAGICRESIST) {
            throw new \InvalidArgumentException('Supplied type is not valid for a defense stat');
        }
        return true;
    }
}

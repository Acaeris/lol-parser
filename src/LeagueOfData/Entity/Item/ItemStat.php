<?php

namespace LeagueOfData\Entity\Item;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Entity\StatInterface;

/**
 * Item Stats
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ItemStat implements StatInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $key;

    /**
     * Create stat object
     * @param string $key
     * @param float $value
     */
    public function __construct(string $key, float $value)
    {
        $this->constructImmutable();

        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Stat key
     * @return string
     */
    public function key() : string
    {
        return $this->key;
    }

    /**
     * Stat value
     * @return float
     */
    public function value() : float
    {
        return $this->value;
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Champion resource data as an array
     */
    public function toArray() : array
    {
        return [ $this->key => $this->value ];
    }
}

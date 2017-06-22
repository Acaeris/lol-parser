<?php

namespace LeagueOfData\Entity;

/**
 * Generic Entity definition
 *
 * @package LeagueOfData\Entity
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface EntityInterface
{
    /**
     * Get key identifying data for the object
     */
    public function getKeyData() : array;
}

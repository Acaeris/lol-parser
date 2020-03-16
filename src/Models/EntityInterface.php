<?php

namespace App\Models;

/**
 * Generic Entity definition
 *
 * @package LeagueOfData\Models
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

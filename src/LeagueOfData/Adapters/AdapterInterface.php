<?php

namespace LeagueOfData\Adapters;

/**
 * Adapter Interface
 *
 * @package LeagueOfData\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface AdapterInterface
{
    /**
     * Fetch data interface
     */
    public function fetch(string $query, array $params) : array;
}

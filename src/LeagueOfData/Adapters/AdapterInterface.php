<?php

namespace LeagueOfData\Adapters;

use LeagueOfData\Adapters\RequestInterface;

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
    public function fetch(RequestInterface $request) : array;
}

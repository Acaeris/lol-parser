<?php

namespace App\Adapters;

/**
 * Adapter Interface
 *
 * @package App\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface AdapterInterface
{
    /**
     * Set adapter options. (Fluid interface)
     */
    public function setOptions(string $query, array $params) : AdapterInterface;

    /**
     * Fetch data interface
     */
    public function fetch() : array;
}

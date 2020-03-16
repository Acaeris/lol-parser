<?php

namespace App\Services\RiotAPI;

use Psr\Http\Message\ResponseInterface;

/**
 * Adapter Interface
 *
 * @package LeagueOfData\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface ApiAdapterInterface
{
    /**
     * Fetch data interface
     *
     * @param string $url
     * @param string[] $params
     * @return ResponseInterface
     */
    public function fetch(string $url, array $params) : ResponseInterface;
}

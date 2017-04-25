<?php

namespace LeagueOfData\Adapters;

use LeagueOfData\Adapters\RequestInterface;

/**
 * Adapter Interface
 * @package LeagueOfData\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface AdapterInterface
{
    /**
     * Fetch data interface
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return array Fetch response
     */
    public function fetch(RequestInterface $request) : array;

    /**
     * Insert object interface
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return stdClass Insert response
     */
    public function insert(RequestInterface $request);

    /**
     * Update object interface
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return stdClass Update response.
     */
    public function update(RequestInterface $request);
}

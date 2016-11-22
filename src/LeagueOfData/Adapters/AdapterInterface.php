<?php

namespace LeagueOfData\Adapters;

use LeagueOfData\Adapters\RequestInterface;

interface AdapterInterface
{
    public function fetch(RequestInterface $request);
    public function insert(RequestInterface $request);
    public function update(RequestInterface $request);
}

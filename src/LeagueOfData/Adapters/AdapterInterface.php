<?php

namespace LeagueOfData\Adapters;

interface AdapterInterface
{
    public function fetch($type, $where);
    public function insert($type, $data);
    public function update($type, $data, $where);
}

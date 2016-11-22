<?php

namespace LeagueOfData\Adapters;

interface RequestInterface
{
    const REQUEST_JSON = 'json';
    const REQUEST_SQL = 'sql';

    public function outputFormat($format);
    public function data();
    public function query();
    public function type();
    public function where();
}

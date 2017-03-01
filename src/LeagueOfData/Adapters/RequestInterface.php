<?php

namespace LeagueOfData\Adapters;

interface RequestInterface
{
    /* @var string JSON Format type */
    const REQUEST_JSON = 'json';
    /* @var string SQL Format Type */
    const REQUEST_SQL = 'sql';

    public function requestFormat(string $format);
    public function data() : array;
    public function query() : string;
    public function type() : string;
    public function where() : array;
}

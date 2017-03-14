<?php

namespace LeagueOfData\Adapters;

interface RequestInterface
{
    /* @var string JSON Format type */
    const REQUEST_JSON = 'json';
    /* @var string SQL Format Type */
    const REQUEST_SQL = 'sql';
    /* @var array Valid Regions for API requests */
    const VALID_REGIONS = ['na', 'euw', 'eune'];

    /**
     * Set format request will be in
     *
     * @var string $format Request Format
     */
    public function requestFormat(string $format);

    /**
     * Data used for request
     *
     * @return array Data used for request
     */
    public function data() : array;

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string;

    /**
     * Type of request
     *
     * @return string Request Type
     */
    public function type() : string;

    /**
     * Where parameters for request
     *
     * @return array Request parameters
     */
    public function where() : array;

    /**
     * Validate request parameters
     * 
     * @var array $where Where parameters
     * @var string|null $query Query string
     * @var array|null $data Request data
     */
    public function validate(array $where, string $query = null, array $data = null);
}

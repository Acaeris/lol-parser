<?php

namespace LeagueOfData\Adapters;

/**
 * Interface for service requests
 *
 * @package  LeagueOfData\Adapters
 * @author   Caitlyn Osborne <acaeris@gmail.com>
 * @link     http://log.gg League Of Data
 */
interface RequestInterface
{
    /**
     * Set format request will be in
     *
     * @param string $format Request Format
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
     * Return request type
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
     * @param array  $where   Where parameters
     * @param string $columns Columns
     * @param array  $data    Request data
     */
    public function validate(array $where, string $columns = '*', array $data = null);
}

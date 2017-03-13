<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

final class VersionRequest implements RequestInterface
{
    /* @var string API Request URL */
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/versions';
    /* @var string Request Type */
    const TYPE = 'version';

    /* @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw' ];
    /* @var string Output Format */
    private $format;
    /* @var array Data to be used in request */
    private $data;
    /* @var string Request query */
    private $query;
    /* @var array Where parameters of request */
    private $where;

    public function __construct(array $where, string $query = null, array $data = null)
    {
        $this->where = $where;
        $this->data = $data;
        $this->query = $query;
    }

    /**
     * Set format request will be in
     *
     * @var string Request Format
     */
    public function requestFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * Data used for request
     *
     * @var array Data used for request
     */
    public function data() : array
    {
        return $this->data;
    }

    /**
     * Type of request
     *
     * @return string Request Type
     */
    public function type() : string
    {
        return self::TYPE;
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === RequestInterface::REQUEST_JSON) {
            $params = array_merge($this->apiDefaults, $this->where);
            return str_replace('{region}', $params['region'], self::API_URL);
        }
        return $this->query;
    }

    /**
     * Where parameters for request
     *
     * @return array Request parameters
     */
    public function where() : array
    {
        if ($this->format === RequestInterface::REQUEST_JSON) {
            return array_merge($this->apiDefaults, $this->where);
        }
        return $this->where;
    }
}

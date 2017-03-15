<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

final class RealmRequest implements RequestInterface
{
    /* @var string API Request URL */
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/realm';
    /* @var string Request Type */
    const TYPE = "realm";
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
        $this->validate($where, $query, $data);
        $this->where = $where;
        $this->data = $data;
        $this->query = $query;
    }

    /**
     * Validate request parameters
     * 
     * @var array $where Where parameters
     * @var string|null $query Query string
     * @var array|null $data Request data
     */
    public function validate(array $where, string $query = null, array $data = null)
    {
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Realm request");
        }
        // TODO: Add validation
    }

    /**
     * Set format request will be in
     *
     * @var string $format Request Format
     */
    public function requestFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * Data used for request
     *
     * @return array Data used for request
     */
    public function data() : array
    {
        return $this->data;
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
     * Type of request
     *
     * @return string Request Type
     */
    public function type() : string
    {
        return self::TYPE;
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
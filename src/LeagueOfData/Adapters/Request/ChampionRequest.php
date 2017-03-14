<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

final class ChampionRequest implements RequestInterface
{
    /* @var string API Request URL */
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/champion';
    /* @var string Request Type */
    const TYPE = 'champion';
    /* @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw', 'champData' => 'all' ];
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
    public function validate(array $where, string $query = null, array $data = null) {
        if (isset($where['id']) && !is_int($where['id'])) {
            throw new \InvalidArgumentException("Invalid ID supplied for Champion request");
        }
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Champion request");
        }
        // TODO: Add version validation
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
     * Set format request will be in
     *
     * @var string Request Format
     */
    function requestFormat(string $format)
    {
        $this->format = $format;
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
            return str_replace('{region}', $params['region'], self::API_URL)
                . (isset($params['id']) ? '/' . $params['id'] : '');
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

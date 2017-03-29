<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

/**
 * Request object for Version Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class VersionRequest implements RequestInterface
{
    /* @var string API Request URL */
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/versions';
    /* @var string Request Type */
    const TYPE = 'versions';

    /* @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw' ];
    /* @var string Output Format */
    private $format;
    /* @var array Data to be used in request */
    private $data;
    /* @var string Requested columns */
    private $columns;
    /* @var array Where parameters of request */
    private $where;

    /**
     * Construct Version Request
     *
     * @param array  $where
     * @param string $columns
     * @param array  $data
     */
    public function __construct(array $where, string $columns = null, array $data = null)
    {
        $this->validate($where, $columns, $data);
        $this->where = $where;
        $this->data = $data;
        $this->columns = $columns;
    }

    /**
     * Validate request parameters
     *
     * @param array       $where   Where parameters
     * @param string|null $columns Requested Columns
     * @param array|null  $data    Request data
     */
    public function validate(array $where, string $columns = null, array $data = null)
    {
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Version request");
        }
        // TODO: add validation
    }

    /**
     * Set format request will be in
     *
     * @param string $format Request Format
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

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        $where = count($parts) > 0 ? " WHERE ".implode(" AND ", $parts) : '';

        return "SELECT {$this->columns} FROM ".self::TYPE.$where;
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

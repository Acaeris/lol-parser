<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

/**
 * Request object for Item Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class ItemRequest implements RequestInterface
{
    /* @var string API Request URL */
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/'.'{region}/v1.2/item';
    /* @var string Request Type */
    const TYPE = "items";
    /* @var array Default parameters for API query */
    private $apiDefaults = [
        'region' => 'euw',
        'itemListData' => 'all',
        'itemData' => 'all',
    ];
    /* @var string Output Format */
    private $format;
    /* @var array Data to be used in request */
    private $data;
    /* @var string Requested columns */
    private $columns;
    /* @var array Where parameters of request */
    private $where;

    /**
     * Construct Item Request
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
     * @param string|null $columns Requested columns
     * @param array|null  $data    Request data
     */
    public function validate(array $where, string $columns = null, array $data = null)
    {
        if (isset($where['id']) && !is_int($where['id'])) {
            throw new \InvalidArgumentException("Invalid ID supplied for Item request");
        }
        if (isset($where['item_id']) && !is_int($where['item_id'])) {
            throw new \InvalidArgumentException("Invalid ID supplied for Item request");
        }
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Item request");
        }
        // TODO: Add version validation
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
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === RequestInterface::REQUEST_JSON) {
            $params = array_merge($this->apiDefaults, $this->where);

            return str_replace('{region}', $params['region'], self::API_URL).(isset($params['id']) ? '/'.$params['id'] : '');
        }

        $parts = [];

        foreach ($this->where as $key => $value) {
            $parts[] = "{$key} = :{$key}";
        }

        return "SELECT ".$this->columns." FROM ".self::TYPE." WHERE ".implode(" AND ", $parts);
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

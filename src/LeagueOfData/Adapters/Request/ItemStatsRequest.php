<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

/**
 * Request object for Item Stats
 *
 * @package LeagueOfData|Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ItemStatsRequest implements RequestInterface
{
    /* @var string Request Type */
    const TYPE = 'item_stats';
    /* @var string Output Format */
    private $format;
    /* @var array Data to be used in request */
    private $data;
    /* @var string Requested columns */
    private $columns;
    /* @var array Where parameters of request */
    private $where;

    /**
     * Construct Item Stats request
     * @param array       $where
     * @param string|null $columns
     * @param array|null  $data
     */
    public function __construct(array $where, string $columns = null, array $data = null)
    {
        $this->where = $where;
        $this->columns = $columns;
        $this->data = $data;
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
     * Data used for request
     *
     * @return array Data used for request
     */
    public function data() : array
    {
        return $this->data;
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
     * Where parameters for request
     *
     * @return array Request parameters
     */
    public function where() : array
    {
        return $this->where;
    }

    /**
     * Validate request parameters
     *
     * @param array       $where   Where parameters
     * @param string|null $columns Columns requested
     * @param array|null  $data    Request data
     */
    public function validate(array $where, string $columns = null, array $data = null)
    {
        if (isset($where['item_id']) && !is_int($where['item_id'])) {
            throw new \InvalidArgumentException('Invalid ID supplied for Item Stats request');
        }
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL query
     */
    public function query() : string
    {
        if ($this->format == RequestInterface::REQUEST_JSON) {
            throw new \Exception('Cannot create API query for stats alone');
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        return "SELECT {$this->columns} FROM ".self::TYPE." WHERE ".implode(" AND ", $parts);
    }
}

<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

/**
 * Request object for Champion Stats
 *
 * @package LeagueOfData|Adapters\Request
 * @author 
 */
class ChampionStatsRequest implements RequestInterface
{
    /* @var string Request Type */
    const TYPE = 'champion_stats';
    /* @var string Output Format */
    private $format;
    /* @var array Data to be used in request */
    private $data;
    /* @var string Requested columns */
    private $columns;
    /* @var array Where parameters of request */
    private $where;

    /**
     * Construct Champion Stats request
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
     * Data used for request
     *
     * @return array Data used for request
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL query
     */
    public function query(): string
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
     * Type of request
     *
     * @return string Request Type
     */
    public function type(): string
    {
        return self::TYPE;
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
        if (isset($where['champion_id']) && !is_int($where['champion_id'])) {
            throw new \InvalidArgumentException('Invalid ID supplied for Champion Stats request');
        }
    }

    /**
     * Where parameters for request
     *
     * @return array Request parameters
     */
    public function where(): array
    {
        return $this->where;
    }

}

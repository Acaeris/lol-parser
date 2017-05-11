<?php

namespace LeagueOfData\Adapters;

/**
 * Request Abstract
 *
 * @package  LeagueOfData|Adapters\Request
 * @author   Caitlyn Osborne <acaeris@gmail.com>
 * @link     http://lod.gg League of Data
 */
abstract class Request implements RequestInterface
{
    /* @var string JSON Format type */
    const TYPE_JSON = 'json';
    /* @var string SQL Format Type */
    const TYPE_SQL = 'sql';
    /* @var array Valid Regions for API requests */
    const VALID_REGIONS = ['na', 'euw', 'eune'];

    /** @var string Output Format */
    protected $format;
    /** @var array Data to be used in request */
    protected $data;
    /** @var string Requested columns */
    protected $columns;
    /** @var array Where parameters of request */
    protected $where;

    public function __construct(array $where, string $columns = '*', array $data = null)
    {
        $this->validate($where, $columns, $data);
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
    public function where(): array
    {
        return $this->where;
    }
}

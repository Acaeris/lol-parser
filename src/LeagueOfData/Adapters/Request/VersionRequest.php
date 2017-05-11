<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Version Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class VersionRequest extends Request
{
    /* @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw' ];

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
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'versions';
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === Request::TYPE_JSON) {
            return 'static-data/v3/versions';
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        $where = count($parts) > 0 ? " WHERE ".implode(" AND ", $parts) : '';

        return "SELECT {$this->columns} FROM versions".$where;
    }

    /**
     * Where parameters for request
     *
     * @return array Request parameters
     */
    public function where() : array
    {
        if ($this->format === Request::TYPE_JSON) {
            return array_merge($this->apiDefaults, $this->where);
        }

        return $this->where;
    }
}

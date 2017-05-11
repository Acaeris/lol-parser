<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Champion Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class ChampionRequest extends Request
{
    /** @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw', 'champListData' => 'all' ];

    /**
     * Validate request parameters
     *
     * @param array       $where   Where parameters
     * @param string      $columns Columns requested
     * @param array|null  $data    Request data
     */
    public function validate(array $where, string $columns = '*', array $data = null)
    {
        if (isset($where['id']) && filter_var($where['id'], FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException("Invalid ID supplied for Champion request");
        }
        if (isset($where['champion_id']) && filter_var($where['champion_id'], FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException("Invalid ID supplied for Champion request");
        }
        // TODO: Move validation out of here.
    }

    /**
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'champions';
    }

    /**
     * Source of the request
     *
     * @return string API endpoint || SQL query
     */
    public function query() : string
    {
        if ($this->format === Request::TYPE_JSON) {
            return 'static-data/v3/champions';
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        $where = count($parts) > 0 ? " WHERE ".implode(" AND ", $parts) : '';

        return "SELECT {$this->columns} FROM champions".$where;
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

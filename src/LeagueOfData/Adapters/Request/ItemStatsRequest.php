<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Item Stats
 *
 * @package LeagueOfData|Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ItemStatsRequest extends Request
{
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
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'item_stats';
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL query
     */
    public function query() : string
    {
        if ($this->format == Request::TYPE_JSON) {
            throw new \Exception('Cannot create API query for stats alone');
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        return "SELECT {$this->columns} FROM item_stats WHERE ".implode(" AND ", $parts);
    }
}

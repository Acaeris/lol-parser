<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Item Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class ItemRequest extends Request
{
    /* @var array Default parameters for API query */
    private $apiDefaults = [
        'region' => 'euw',
        'itemListData' => 'all',
        'itemData' => 'all',
    ];

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
        // TODO: Move validation out of here.
    }

    /**
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'items';
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === Request::TYPE_JSON) {
            return 'static-data/v3/items';
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        $where = count($parts) > 0 ? " WHERE ".implode(" AND ", $parts) : '';

        return "SELECT {$this->columns} FROM items".$where;
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

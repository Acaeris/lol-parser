<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Champion Passive
 *
 * @package  LeagueOfData\Adapters\Request
 * @author   Caitlyn Osborne <acaeris@gmail.com>
 * @link     http://lod.gg League of Data
 */
class ChampionPassiveRequest extends Request
{
    /**
     * Source of the request
     *
     * @return string API url || SQL query
     */
    public function query() : string
    {
        if ($this->format == Request::TYPE_JSON) {
            throw new \Exception('Cannot create API query for passive alone');
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        return "SELECT {$this->columns} FROM champion_passives WHERE ".implode(" AND ", $parts);
    }

    /**
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'champion_passives';
    }

    /**
     * Validate request parameters
     *
     * @param array       $where   Where parameters
     * @param string      $columns Columns requested
     * @param array|null  $data    Request data
     */
    public function validate(array $where, string $columns = '*', array $data = null)
    {
        if (isset($where['champion_id']) && filter_var($where['champion_id'], FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException('Invalid ID supplied for Champion Passive request');
        }
    }
}

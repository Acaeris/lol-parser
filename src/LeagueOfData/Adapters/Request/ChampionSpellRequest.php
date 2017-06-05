<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Champion Spells
 *
 * @package  LeagueOfData|Adapters\Request
 * @author   Caitlyn Osborne <acaeris@gmail.com>
 * @link     http://lod.gg League of Data
 */
class ChampionSpellRequest extends Request
{
    public function query(): string
    {
        if ($this->format == Request::TYPE_JSON) {
            throw new \Exception('Cannot create API query for spells alone');
        }

        $parts = [];

        while (list($key, ) = each($this->where)) {
            $parts[] = "{$key} = :{$key}";
        }

        return "SELECT {$this->columns} FROM champion_spells WHERE " . implode(" AND ", $parts)
            . " ORDER BY CASE WHEN spell_key = 'Q' THEN 0"
            . " WHEN spell_key = 'W' THEN 1"
            . " WHEN spell_key = 'E' THEN 2"
            . " ELSE 3 END";
    }

    /**
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'champion_spells';
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
            throw new \InvalidArgumentException('Invalid ID supplied for Champion Stats request');
        }
    }

}

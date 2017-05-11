<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Match List Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class MatchListRequest extends Request
{

    /* @var string API Request URL */
    const API_URL = 'https://{region}.api.pvp.net/api/lol/{region}/v2.2/matchlist/by-summoner/';
    /* @var array Default parameters for API query */
    private $apiDefaults = [ 'region' => 'euw' ];

    /**
     * Validate request parameters
     *
     * @param array       $where Where parameters
     * @param string|null $query Query string
     * @param array|null  $data  Request data
     */
    public function validate(array $where, string $query = null, array $data = null)
    {
        if (isset($where['id']) && !is_int($where['id'])) {
            throw new \InvalidArgumentException("Invalid ID supplied for Summoner Match List request");
        }
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Summoner Match List request");
        }
        // TODO: Add version validation
    }

    /**
     * Returns request type
     *
     * @return string Request type
     */
    public function type() : string
    {
        return 'match-list';
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === Request::TYPE_JSON) {
            $params = array_merge($this->apiDefaults, $this->where);

            return str_replace('{region}', $params['region'], self::API_URL);
        }

        return $this->columns;
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

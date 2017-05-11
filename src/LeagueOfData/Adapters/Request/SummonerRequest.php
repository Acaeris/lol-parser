<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\Request;

/**
 * Request object for Summoner Services
 *
 * @package LeagueOfData\Adapters\Request
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SummonerRequest extends Request
{
    /** @var string Endpoint */
    const ENDPOINT = 'summoner/v3';
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
        if (!isset($where['id']) && !isset($where['name'])) {
            throw new \InvalidArgumentException('No ID or Name supplied for Summoner request');
        }
        if (isset($where['id']) && !is_int($where['id'])) {
            throw new \InvalidArgumentException("Invalid ID supplied for Summoner request");
        }
        if (isset($where['region']) && !in_array($where['region'], self::VALID_REGIONS)) {
            throw new \InvalidArgumentException("Invalid Region supplied for Summoner request");
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
        return 'summoners';
    }

    /**
     * Source of the request
     *
     * @return string API url || SQL table
     */
    public function query() : string
    {
        if ($this->format === Request::TYPE_JSON) {
            $url = self::ENDPOINT.'/summoners';
            if (isset($this->where['name'])) {
                $url .= "/by-name/" . $this->where['name'];
            }
            if (isset($this->where['id'])) {
                $url .= "/" . $this->where['id'];
            }
            return $url;
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

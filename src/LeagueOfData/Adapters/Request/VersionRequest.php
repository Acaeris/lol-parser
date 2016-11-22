<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

final class VersionRequest implements RequestInterface
{
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/versions';
    const TYPE = 'version';

    private $apiDefaults = [ 'region' => 'euw' ];
    private $format;
    private $data;
    private $query;
    private $where;

    public function __construct($where, $query = null, $data = null)
    {
        $this->where = $where;
        $this->data = $data;
        $this->query = $query;
    }

    public function outputFormat($format)
    {
        $this->format = $format;
    }

    public function data()
    {
        return $this->data;
    }

    public function type()
    {
        return self::TYPE;
    }

    public function query()
    {
        if ($this->format === RequestInterface::REQUEST_JSON) {
            $params = array_merge($this->apiDefaults, $this->where);
            return str_replace('{region}', $params['region'], self::API_URL);
        }
        return $this->query;
    }

    public function where()
    {
        if ($this->format === RequestInterface::REQUEST_JSON) {
            return array_merge($this->apiDefaults, $this->where);
        }
        return $this->where;
    }
}

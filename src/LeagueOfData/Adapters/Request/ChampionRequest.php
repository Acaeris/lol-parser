<?php

namespace LeagueOfData\Adapters\Request;

use LeagueOfData\Adapters\RequestInterface;

final class ChampionRequest implements RequestInterface
{
    const API_URL = 'https://global.api.pvp.net/api/lol/static-data/{region}/v1.2/champion';
    const TYPE = 'champion';

    private $apiDefaults = [ 'region' => 'euw', 'champData' => 'all' ];
    private $format;
    private $data;
    private $query;
    private $where;

    public function __construct($where, $query = null, $data = null)
    {
        $this->validate($where);
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
            return str_replace('{region}', $params['region'], self::API_URL)
                . (isset($params['id']) ? '/' . $params['id'] : '');
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

    private function validate($where) {
        if (isset($where['id']) && !is_int($where['id'])) {
            throw new \Exception("Invalid ID supplied for Champion request");
        }
        // TODO: Add version validation
    }
}

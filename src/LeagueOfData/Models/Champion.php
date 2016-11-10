<?php

namespace LeagueOfData\Models;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Models\Interfaces\Champion as ChampionInterface;

final class Champion implements ChampionInterface {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function name() {
        return $this->data['name'];
    }

    public function toArray() {
        return $this->data;
    }

    public function store(AdapterInterface $adapter)
    {
        if ($adapter->fetch('champion', [
            'query' => 'SELECT name FROM leagueOfData.champion WHERE id = ? AND version = ?',
            'params' => [ $this->data['id'], $this->data['version'] ]
        ])) {
            $adapter->update('champion', $this->data, [ 'id' => $this->data['id'], 'version' => $this->data['version'] ]);
        } else {
            $adapter->insert('champion', $this->data);
        }
    }
}

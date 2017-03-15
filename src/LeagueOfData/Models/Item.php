<?php

namespace LeagueOfData\Models;

use LeagueOfData\Adapters\AdapterInterface;

final class Item
{
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

    public function store(AdapterInterface $adapter) {
        if ($adapter->fetch('item', [
            'query' => 'SELECT name FROM leagueOfData.item WHERE id = ? AND version = ?',
            'params' => [ $this->data['id'], $this->data['version'] ]
        ])) {
            $adapter->update('item', $this->data, [ 'id' => $this->data['id'], 'version' => $this->data['version'] ]);
            return;
        }
        $adapter->insert('item', $this->data);
    }
}

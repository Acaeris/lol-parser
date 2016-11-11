<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\Interfaces\Items;
use LeagueOfData\Models\Item;
use LeagueOfData\Adapters\AdapterInterface;

final class JsonItems implements Items
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function collectAll($version)
    {
        $response = $this->source->fetch('item', [ 'version' => $version ]);
        $items = [];
        foreach ($response->data as $item) {
            $items[] = $this->create($item, $response->version);
        }
        return $items;
    }

    public function collect($id, $version)
    {
        $response = $this->source->fetch('item', ['id' => $id, 'region' => 'euw', 'version' => $version]);
        return $this->create($response, $version);
    }

    private function create($item, $version)
    {
        return new Item([
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->sanitizedDescription,
            'image' => $item->image->full,
            'version' => $version
        ]);
    }
}

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
            try {
                $items[] = $this->create($item, $response->version);
            } catch (\Exception $e) {
                var_dump($item); echo $e; die();
            }
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
            'name' => (isset($item->name) ? $item->name : 'N/A'),
            'description' => (isset($item->sanitizedDescription) ? $item->sanitizedDescription : 'N/A'),
            'image' => $item->image->full,
            'version' => $version
        ]);
    }
}

<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

final class JsonItems implements ItemServiceInterface
{
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var array Item Objects */
    private $items;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    /**
     * Add an item to the collection
     *
     * @param Item $item
     */
    public function add(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Fetch Items
     *
     * @param string $version
     * @param int    $itemId
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null) : array
    {
        $this->log->info("Fetching items from API for version: {$version}".(isset($itemId) ? " [{$itemId}]" : ""));

        $region = 'euw';
        $params = [ 'version' => $version, 'region' => $region ];

        if (isset($itemId) && !empty($itemId)) {
            $params['id'] = $itemId;
        }

        $request = new ItemRequest($params);
        $response = $this->source->fetch($request);
        $this->items = [];

        if ($response !== false) {
            if (!isset($response->data)) {
                $temp = new \stdClass();
                $temp->data = [ $response ];
                $response = $temp;
            }

            foreach ($response->data as $item) {
                $this->items[] = $this->create($item, $version);
            }
        }

        return $this->items;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        $this->log->error("Request to store data through JSON API not available.");
    }

    /**
     * Collection of Item objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->items;
    }

    /**
     * Create the item object from array data
     *
     * @param array $item
     * @param string $version
     * @return Item
     */
    private function create(\stdClass $item, string $version) : Item
    {
        return new Item(
            $item->id,
            (isset($item->name) ? $item->name : ''),
            (isset($item->description) ? $item->description : ''),
            (isset($item->gold->total) ? $item->gold->total : ''),
            (isset($item->gold->sell) ? $item->gold->sell : ''),
            $version
        );
    }
}

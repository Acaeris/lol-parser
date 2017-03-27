<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Adapters\Request\ItemRequest;

/**
 * Item object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlItems implements ItemServiceInterface
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Version objects */
    private $items = [];

    /**
     * Setup item factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->dbAdapter = $adapter;
        $this->log = $log;
    }

    /**
     * Add an item object to internal array
     *
     * @param Item $item
     */
    public function add(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Add all item objects to internal array
     *
     * @param array $items Item objects
     */
    public function addAll(array $items)
    {
        $this->items = array_merge($this->items, $items);
    }

    /**
     * Store the item objects in the DB
     */
    public function store()
    {
        foreach ($this->items as $item) {
            $request = new ItemRequest(
                [ 'id' => $item->getID() ],
                'SELECT id FROM item WHERE id = :id',
                $item->toArray()
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);
                continue;
            }

            $this->dbAdapter->insert($request);
        }
    }

    /**
     * Transfer objects out to another service
     *
     * @return array Item objects
     */
    public function transfer() : array
    {
        return $this->items;
    }

    /**
     * Fetch Items
     *
     * @param string $version
     * @param int    $itemId
     *
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null): array
    {
        $this->log->info("Fetching items from DB for version: {$version}".(isset($itemId) ? " [{$itemId}]" : ""));

        $request = new ItemRequest(
            [ 'version' => $version ],
            'SELECT * FROM item WHERE version = :version'
        );

        if (isset($itemId) && !empty($itemId)) {
            $request = new ItemRequest(
                [ 'id' => $itemId, 'version' => $version ],
                'SELECT * FROM item WHERE id = :id AND version = :version'
            );
        }

        $this->items = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $item) {
                $this->items[] = Item::fromState($item);
            }
        }

        return $this->items;
    }

}

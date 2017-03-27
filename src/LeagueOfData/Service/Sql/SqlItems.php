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
            } else {
                $this->dbAdapter->insert($request);
            }
        }
    }

    /**
     * Find all Item data
     *
     * @param string $version Version to fetch for.
     *
     * @return array Item objects
     */
    public function findAll(string $version) : array
    {
        $this->items = [];
        $results = $this->dbAdapter->fetch('item', [
            'query' => 'SELECT * FROM item WHERE version = ?',
            'params' => [$version],
        ]);

        if ($results === false) {
            return [];
        }

        foreach ($results as $item) {
            $this->items[] = new Item($item);
        }

        return $this->items;
    }


    /**
     * Find a specific Item
     *
     * @param int    $id      Item ID
     * @param string $version Version to fetch for
     *
     * @return array Item objects
     */
    public function find(int $id, string $version) : array
    {
        $result = $this->dbAdapter->fetch('item', [
            'query' => 'SELECT * FROM item WHERE id = ? AND version = ?',
            'params' => [$id, $version],
        ]);
        $this->items = [ new Item($result) ];

        return $this->items;
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
}

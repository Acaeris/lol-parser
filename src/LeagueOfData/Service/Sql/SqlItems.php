<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request;
use LeagueOfData\Entity\Item\Item;
use LeagueOfData\Entity\Item\ItemStat;
use LeagueOfData\Entity\EntityInterface;

/**
 * Item object SQL factory.
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlItems implements StoreServiceInterface
{
    /**
     * @var Connection DB connection
     */
    private $dbConn;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Version objects
     */
    private $items = [];

    /**
     * Setup item factory service
     *
     * @param Connection      $connection
     * @param LoggerInterface $log
     */
    public function __construct(Connection $connection, LoggerInterface $log)
    {
        $this->dbConn = $connection;
        $this->log = $log;
    }

    /**
     * Add item objects to internal array
     *
     * @param array $items Item objects
     */
    public function add(array $items)
    {
        foreach ($items as $item) {
            $this->items[$item->getItemID()] = $item;
        }
    }

    /**
     * Build the Item object from the given data.
     *
     * @param array $item
     * @return EntityInterface
     */
    public function create(array $item) : EntityInterface
    {
        return new Item(
            $item['item_id'],
            $item['item_name'],
            $item['description'],
            $item['purchase_value'],
            $item['sale_value'],
            $item['stats'],
            $item['version'],
            $item['region']
        );
    }

    /**
     * Store the item objects in the DB
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->items)." new/updated items");

        foreach ($this->items as $item) {
            $select = "SELECT item_id FROM items WHERE item_id = :item_id AND version = :version";

            $this->storeStats($item);

            if ($this->dbConn->fetchAll($select, $item->getKeyData())) {
                $this->dbConn->update('items', $this->convertItemToArray($item), $item->getKeyData());
                continue;
            }

            $this->dbConn->insert('items', $this->convertItemToArray($item));
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
     * @param RequestInterface $request
     * @return array Item Objects
     */
    public function fetch(RequestInterface $request): array
    {
        $this->log->debug("Fetching items from DB");
        $request->requestFormat(Request::TYPE_SQL);
        $results = $this->dbConn->fetchAll($request->query(), $request->where());
        $this->items = [];
        $this->processResults($results);
        $this->log->debug(count($this->items)." items fetched from DB");

        return $this->items;
    }

    /**
     * Fetch the stats for the given item
     *
     * @param array $item
     *
     * @return array
     */
    private function fetchStats(array $item) : array
    {
        $select = "SELECT * FROM item_stats WHERE item_id = :item_id AND version = :version AND region = :region";
        $where = ['item_id' => $item['item_id'], 'version' => $item['version'], 'region' => $item['region']];
        $stats = [];
        $results = $this->dbConn->fetchAll($select, $where);

        if ($results !== false) {
            foreach ($results as $stat) {
                $stats[] = new ItemStat($stat['stat_name'], $stat['stat_value']);
            }
        }

        return $stats;
    }

    /**
     * Store the item stats in the database
     *
     * @param Item $item
     */
    private function storeStats(Item $item)
    {
        foreach ($item->getStats() as $key => $value) {
            $select = "SELECT item_id FROM item_stats WHERE item_id = :item_id AND version = :version "
                . "AND stat_name = :stat_name AND region = :region";
            $where = $item->getKeyData();
            $where['stat_name'] = $key;
            $data = array_merge($where, ['stat_value' => $value]);

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('item_stats', $data, $where);

                return;
            }

            $this->dbConn->insert('item_stats', $data);
        }
    }

    /**
     * Converts Item object into SQL data array
     *
     * @param Item $item
     *
     * @return array
     */
    private function convertItemToArray(Item $item) : array
    {
        return [
            'item_id' => $item->getItemID(),
            'item_name' => $item->getName(),
            'description' => $item->getDescription(),
            'purchase_value' => $item->getGoldToBuy(),
            'sale_value' => $item->getGoldFromSale(),
            'version' => $item->getVersion(),
            'region' => $item->getRegion()
        ];
    }

    /**
     * Convert result data into Item objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $item) {
                $item['stats'] = $this->fetchStats($item);
                $this->items[$item['item_id']] = $this->create($item);
            }
        }
    }
}

<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Adapters\Request\ItemStatsRequest;
use LeagueOfData\Models\Item\Item;
use Psr\Log\LoggerInterface;

class SqlItemsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch(new ItemRequest(['version' => '7.4.3'], '*'))->willReturn([
            [
                "item_id" => 1001,
                "item_name" => "Boots of Speed",
                "description" => "Test Description",
                "purchase_value" => 300,
                "sale_value" => 210,
                "version" => "7.4.3",
            ],
            [
                "item_id" => 1002,
                "item_name" => "Test Item",
                "description" => "Test Description",
                "purchase_value" => 300,
                "sale_value" => 210,
                "version" => "7.4.3",
            ],
        ]);
        $adapter->fetch(new ItemRequest(['item_id' => 1001, 'version' => '7.4.3'], '*'))->willReturn([
            [
                "item_id" => 1001,
                "item_name" => "Boots of Speed",
                "description" => "Test Description",
                "purchase_value" => 300,
                "sale_value" => 210,
                "version" => "7.4.3",
            ],
        ]);
        $adapter->fetch(new ItemStatsRequest(['item_id' => 1001, 'version' => '7.4.3'], '*'))->willReturn([
            [
                "item_id" => 1001,
                "stat_name" => "FlatMoveSpeedMod",
                "stat_value" => 30,
                "version" => "7.4.3",
            ]
        ]);
        $adapter->fetch(new ItemStatsRequest(['item_id' => 1002, 'version' => '7.4.3'], '*'))->willReturn([
            [
                "item_id" => 1002,
                "stat_name" => "TestMod",
                "stat_value" => 30,
                "version" => "7.4.3",
            ]
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlItems');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ItemServiceInterface');
    }

    public function it_should_fetch_all_if_only_version_passed()
    {
        $this->fetch('7.4.3')->shouldReturnArrayOfItems();
    }

    public function it_should_fetch_one_if_version_and_id_passed()
    {
        $this->fetch('7.4.3', 1001)->shouldReturnArrayOfItems();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfItems' => function($items) {
                foreach ($items as $item) {
                    if (!$item instanceof Item) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

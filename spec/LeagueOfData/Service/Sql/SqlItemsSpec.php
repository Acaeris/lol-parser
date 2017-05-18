<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ItemStatsRequest;
use LeagueOfData\Models\Interfaces\ItemInterface;

class SqlItemsSpec extends ObjectBehavior
{
    private $mockData = [
        [
            "item_id" => 1001,
            "item_name" => "Boots of Speed",
            "description" => "Test Description",
            "purchase_value" => 300,
            "sale_value" => 210,
            "version" => "7.4.3",
            "region" => "euw"
        ],
        [
            "item_id" => 1002,
            "item_name" => "Test Item",
            "description" => "Test Description",
            "purchase_value" => 300,
            "sale_value" => 210,
            "version" => "7.4.3",
            "region" => "euw"
        ],
    ];
    private $mockStats = [
        [[
            "item_id" => 1001,
            "stat_name" => "FlatMoveSpeedMod",
            "stat_value" => 30,
            "version" => "7.4.3",
            "region" => "euw"
        ]], [[
            "item_id" => 1002,
            "stat_name" => "TestMod",
            "stat_value" => 30,
            "version" => "7.4.3",
            "region" => "euw"
        ]]
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger, RequestInterface $request)
    {
        $adapter->fetch($request)->willReturn($this->mockData);
        $statRequest = ['item_id' => 1001, 'version' => '7.4.3', 'region' => 'euw'];
        $adapter->fetch(new ItemStatsRequest($statRequest, '*'))->willReturn($this->mockStats[0]);
        $statRequest['item_id'] = 1002;
        $adapter->fetch(new ItemStatsRequest($statRequest, '*'))->willReturn($this->mockStats[1]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlItems');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ItemServiceInterface');
    }

    public function it_should_fetch_item_data(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfItems();
    }

    public function it_can_convert_data_to_item_objects()
    {
        $this->create($this->mockData[0], $this->mockStats[0])
            ->shouldImplement('LeagueOfData\Models\Interfaces\ItemInterface');
    }

    public function it_can_add_and_retrieve_item_objects_from_collection(ItemInterface $item)
    {
        $item->getItemID()->willReturn(1);
        $this->add([$item]);
        $this->transfer()->shouldReturnArrayOfItems();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfItems' => function($items) {
                foreach ($items as $item) {
                    if (!$item instanceof ItemInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

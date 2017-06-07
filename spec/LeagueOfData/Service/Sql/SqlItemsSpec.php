<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Adapters\RequestInterface;
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

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $select = 'SELECT * FROM item_stats WHERE item_id = :item_id AND version = :version AND region = :region';
        $statRequest = ['item_id' => 1001, 'version' => '7.4.3', 'region' => 'euw'];
        $dbConn->fetchAll($select, $statRequest)->willReturn($this->mockStats[0]);
        $statRequest['item_id'] = 1002;
        $dbConn->fetchAll($select, $statRequest)->willReturn($this->mockStats[1]);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlItems');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ItemServiceInterface');
    }

    public function it_should_fetch_item_data(RequestInterface $request)
    {
        $request->query()->shouldBeCalled();
        $request->where()->shouldBeCalled();
        $request->requestFormat('sql')->shouldBeCalled();
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

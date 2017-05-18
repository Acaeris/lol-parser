<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ItemInterface;

class JsonItemsSpec extends ObjectBehavior
{
    private $mockData = [
        "data" => [
            "1001" => [
                "id" => 1001,
                "name" => "Boots of Speed",
                "description" => "Test Description",
                "gold" => [
                    "total" => 300,
                    "sell" => 210,
                ],
                "stats" => [
                    "FlatMovementSpeedMod" => 25,
                ],
                "version" => "7.9.1",
                "region" => "euw"
            ],
            "1002" => [
                "id" => 1002,
                "name" => "Test Item",
                "description" => "Test Description",
                "gold" => [
                    "total" => 300,
                    "sell" => 210,
                ],
                "stats" => [
                    "TestStat" => 100,
                ],
                "version" => "7.9.1",
                "region" => "euw"
            ]
        ]
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger, RequestInterface $request)
    {
        $request->where()->willReturn(['version' => '7.9.1', 'region' => 'euw']);
        $adapter->fetch($request)->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonItems');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ItemServiceInterface');
    }

    public function it_should_fetch_item_data(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfItems();
    }

    public function it_can_convert_data_to_item_object()
    {
        $this->create($this->mockData['data']['1001'], [])->shouldImplement('LeagueOfData\Models\Interfaces\ItemInterface');
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

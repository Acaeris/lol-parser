<?php

namespace spec\LeagueOfData\Repository\Item;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Item\ItemInterface;

class JsonItemRepositorySpec extends ObjectBehavior
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

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Item\JsonItemRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_fetch_item_data(AdapterInterface $adapter)
    {
        $params = ["region" => "euw", "tags" => "all", "version" => "7.9.1"];
        $adapter->setOptions("static-data/v3/items", $params)->willReturn($adapter);
        $this->fetch(['version' => '7.9.1'])->shouldReturnArrayOfItems();
    }

    public function it_can_convert_data_to_item_object()
    {
        $this->create($this->mockData['data']['1001'])
            ->shouldImplement('LeagueOfData\Entity\Item\ItemInterface');
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfItems' => function ($items) {
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

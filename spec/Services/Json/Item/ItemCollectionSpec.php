<?php

namespace spec\App\Service\Json\Item;

use App\Services\FetchServiceInterface;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use App\Adapters\AdapterInterface;
use App\Models\Items\ItemInterface;

class ItemCollectionSpec extends ObjectBehavior
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
        $this->shouldImplement(FetchServiceInterface::class);
    }

    public function it_should_fetch_item_data(AdapterInterface $adapter)
    {
        $params = ["region" => "euw", "itemListData" => "all", "itemData" => "all", "version" => "7.9.1"];
        $adapter->setOptions("static-data/v3/items", $params)->willReturn($adapter);
        $this->fetch(['version' => '7.9.1'])->shouldReturnArrayOfItems();
    }

    public function it_can_convert_data_to_item_object()
    {
        $this->create($this->mockData['data']['1001'])
            ->shouldImplement(ItemInterface::class);
    }

    public function getMatchers(): array
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

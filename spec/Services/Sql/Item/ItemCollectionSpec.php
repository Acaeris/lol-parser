<?php

namespace spec\App\Services\Sql\Item;

use App\Mappers\Items\ItemMapper;
use App\Models\Items\ItemInterface;
use App\Services\StoreServiceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;

class ItemCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        [
            "item_id" => 1001,
            "item_name" => "Boots of Speed",
            "colloq" => "Boots1",
            "image" => [
                'full' => 'full.png',
                'group' => 'champion',
                'sprite' => 'sprite.png',
                'x' => 0,
                'y' => 0,
                'w' => 64,
                'h' => 64
            ],
            "description" => 'Test Description',
            "sanitized_description" => "Test Description",
            "tags" => ['boots'],
            "plain_text" => 'Test Description',
            "stats" => [
                "stat_name" => "FlatMoveSpeedMod",
                "stat_value" => 30,
            ],
            "purchase_value" => 300,
            "sale_value" => 210,
            "effects" => ['Some effects'],
            "hide_from_all" => false,
            "in_store" => true,
            "consume_on_full" => false,
            "consumed" => false,
            "into" => [1002],
            "from" => [],
            "maps" => ['SR', 'ARAM', 'ARURF', 'URF'],
            "special_recipe" => 0,
            "required_champion" => '',
            "group" => 'Movement Speed',
            "depth" => 0,
            "stacks" => 0,
            "region" => "euw",
            "version" => "7.4.3"
        ]
    ];

    private $mockStats = [
        [
            "item_id" => 1001,
            "stat_name" => "FlatMoveSpeedMod",
            "stat_value" => 30,
            "region" => "euw",
            "version" => "7.4.3",
        ]
    ];

    public function let(
        Connection $dbConn,
        LoggerInterface $logger,
        ItemMapper $itemMapper,
        ItemInterface $item
    ) {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $itemMapper->mapFromArray(new AnyValuesToken())->willReturn($item);
        $select = 'SELECT * FROM item_stats WHERE item_id = :item_id AND version = :version AND region = :region';
        $statRequest = ['item_id' => 1001, 'version' => '7.4.3', 'region' => 'euw'];
        $dbConn->fetchAll($select, $statRequest)->willReturn($this->mockStats);
        $this->beConstructedWith($dbConn, $logger, $itemMapper);
    }

    public function it_should_be_initializable()
    {
        $this->shouldImplement(StoreServiceInterface::class);
    }

    public function it_should_fetch_item_data()
    {
        $this->fetch("")->shouldReturnArrayOfItems();
    }

    public function it_can_add_and_retrieve_item_objects_from_collection(ItemInterface $item)
    {
        $item->getItemID()->willReturn(1);
        $this->add([$item]);
        $this->transfer()->shouldReturnArrayOfItems();
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

<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Models\Item\Item;
use Psr\Log\LoggerInterface;

class JsonItemsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new ItemRequest(['version' => '7.4.3', 'region' => 'euw']);
        $adapter->fetch($request)->willReturn([
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
                ],
            ],
            "version" => '7.4.3',
        ]);
        $request = new ItemRequest(['id' => 1001, 'region' => 'euw', 'version' => '7.4.3']);
        $adapter->fetch($request)->willReturn([
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
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonItems');
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

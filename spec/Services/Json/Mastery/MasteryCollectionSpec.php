<?php
namespace spec\App\Services\Json\Mastery;

use App\Mappers\Masteries\MasteryMapper;
use App\Services\FetchServiceInterface;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use App\Adapters\AdapterInterface;
use App\Models\Masteries\MasteryInterface;

class MasteryCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        "data" => [
            "6111" => [
                "prereq" => 0,
                "masteryTree" => "Ferocity",
                "description" => [
                    "+0.8% Attack Speed",
                    "+1.6% Attack Speed",
                    "+2.4% Attack Speed",
                    "+3.2% Attack Speed",
                    "+4% Attack Speed"
                ],
                "ranks" => 5,
                "image" => [
                    "full" => "6111.png",
                    "group" => "mastery",
                    "sprite" => "mastery0.png",
                    "h" => 48,
                    "w" => 48,
                    "y" => 0,
                    "x" => 0
                ],
                "sanitizedDescription" => [
                    "+0.8% Attack Speed",
                    "+1.6% Attack Speed",
                    "+2.4% Attack Speed",
                    "+3.2% Attack Speed",
                    "+4% Attack Speed"
                ],
                "id" => 6111,
                "name" => "Fury",
                "version" => "7.9.1",
                "region" => "euw"
            ]
        ]
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger,
        MasteryMapper $masteryMapper,
        MasteryInterface $mastery
    ) {
        $adapter->fetch()->willReturn($this->mockData);
        $masteryMapper->mapFromArray($this->mockData['data']['6111'])->willReturn($mastery);
        $this->beConstructedWith($adapter, $logger, $masteryMapper);
    }

    public function it_should_be_initializable()
    {
        $this->shouldImplement(FetchServiceInterface::class);
    }

    public function it_should_fetch_mastery_data(AdapterInterface $adapter)
    {
        $params = ["region" => "euw", "tags" => "all", "version" => "7.9.1"];
        $adapter->setOptions("static-data/v3/masteries", $params)->willReturn($adapter);
        $this->fetch(['version' => '7.9.1'])->shouldReturnArrayOfMasteries();
    }

    public function getMatchers(): array
    {
        return [
            'returnArrayOfMasteries' => function(array $masteries) : bool {
                foreach ($masteries as $mastery) {
                    if (!$mastery instanceof MasteryInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

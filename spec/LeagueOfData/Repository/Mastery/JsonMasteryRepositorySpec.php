<?php
namespace spec\LeagueOfData\Repository\Mastery;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Mastery\MasteryInterface;

class JsonMasteryRepositorySpec extends ObjectBehavior
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

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Mastery\JsonMasteryRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_fetch_mastery_data(AdapterInterface $adapter)
    {
        $params = ["region" => "euw", "tags" => "all", "version" => "7.9.1"];
        $adapter->setOptions("static-data/v3/masteries", $params)->willReturn($adapter);
        $this->fetch(['version' => '7.9.1'])->shouldReturnArrayOfMasteries();
    }

    public function it_can_convert_data_to_mastery_object()
    {
        $this->create($this->mockData['data']['6111'])
            ->shouldImplement('LeagueOfData\Entity\Mastery\MasteryInterface');
    }

    public function getMatchers()
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

<?php
namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;

class JsonChampionPassivesSpec extends ObjectBehavior
{

    private $mockData = [
        "id" => 266,
        "passive" => [
            "image" => [
                "full" => "Annie_Passive.png",
                "group" => "passive",
                "sprite" => "passive0.png",
                "h" => 48,
                "w" => 48,
                "y" => 0,
                "x" => 288
            ],
            "sanitizedDescription" => "Test Sanitised Description",
            "name" => "Pyromania",
            "description" => "Test Description"
        ],
        "version" => "7.9.1",
        "region" => "euw"
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger, RequestInterface $request)
    {
        $adapter->fetch($request)->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonChampionPassives');
        $this->shouldImplement('LeagueOfData\Service\FetchServiceInterface');
    }

    public function it_can_convert_data_to_spell_object()
    {
        $this->create($this->mockData)->shouldImplement('LeagueOfData\Entity\Champion\ChampionPassiveInterface');
    }
}

<?php

namespace spec\LeagueOfData\Service\Json\Summoner;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Summoner\SummonerInterface;

class SummonerCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        "id" => 1,
        "accountId" => 2,
        "name" => "Acaeris",
        "summonerLevel" => 30,
        "profileIconId" => 779,
        "revisionDate" => 1498931618000,
        "region" => "euw"
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger
    ) {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\Summoner\SummonerCollection');
        $this->shouldImplement('LeagueOfData\Service\FetchServiceInterface');
    }

    public function it_can_convert_data_to_summoner_object()
    {
        $this->create($this->mockData)
            ->shouldImplement('LeagueOfData\Entity\Summoner\SummonerInterface');
    }

    public function it_should_fetch_summoner_by_id(AdapterInterface $adapter)
    {
        $adapter->setOptions('summoner/v3/summoners/1', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(["summoner_id" => 1])->shouldReturnArrayOfSummoners();
    }

    public function it_should_fetch_summoner_by_name(AdapterInterface $adapter)
    {
        $adapter->setOptions('summoner/v3/summoners/by-name/Acaeris', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(['summoner_name' => 'Acaeris'])->shouldReturnArrayOfSummoners();
    }

    public function it_should_fetch_summoner_by_account_id(AdapterInterface $adapter)
    {
        $adapter->setOptions('summoner/v3/summoners/by-account/2', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(['account_id' => 2])->shouldReturnArrayOfSummoners();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfSummoners' => function($summoners) {
                foreach ($summoners as $summoner) {
                    if (!$summoner instanceof SummonerInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

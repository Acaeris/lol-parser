<?php
namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;

class SqlChampionsSpec extends ObjectBehavior
{
    private $mockData = [
        [
            "champion_id" => 266,
            "champion_name" => "Aatrox",
            "title" => "the Darkin Blade",
            "tags" => "Fighter|Tank",
            "resource_type" => "Blood Well",
            "version" => "7.9.1",
            "region" => "euw"
        ], [
            "champion_id" => 412,
            "champion_name" => "Thresh",
            "title" => "the Chain Warden",
            "tags" => "Support",
            "resource_type" => "Mana",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger, ChampionStatsServiceInterface $statBuilder, ChampionStatsInterface $stats)
    {
        $adapter->fetch(new ChampionRequest(['version' => '7.9.1', 'region' => 'euw'], '*'))
            ->willReturn($this->mockData);
        $adapter->fetch(new ChampionRequest(['champion_id' => 266, 'region' => 'euw', 'version' => '7.9.1'], '*'))
            ->willReturn([$this->mockData[0]]);
        $statBuilder->fetch('7.9.1', 266, 'euw')->willReturn([266 => $stats]);
        $statBuilder->fetch('7.9.1', 412, 'euw')->willReturn([412 => $stats]);
        $this->beConstructedWith($adapter, $logger, $statBuilder);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionServiceInterface');
    }

    public function it_should_fetch_all_if_only_version_passed()
    {
        $this->fetch('7.9.1')->shouldReturnArrayOfChampions();
    }

    public function it_should_fetch_one_if_version_and_id_passed()
    {
        $this->fetch('7.9.1', 266)->shouldReturnArrayOfChampions();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampions' => function($champions) {
                foreach($champions as $champion) {
                    if (!$champion instanceof Champion) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

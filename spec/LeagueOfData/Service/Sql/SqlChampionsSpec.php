<?php
namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;

class SqlChampionsSpec extends ObjectBehavior
{
    private $allRequest;
    private $singleRequest;
    private $mockData = [
        [
            "champion_id" => 266,
            "champion_name" => "Aatrox",
            "image_name" => "Aatrox",
            "title" => "the Darkin Blade",
            "tags" => "Fighter|Tank",
            "resource_type" => "Blood Well",
            "version" => "7.9.1",
            "region" => "euw"
        ], [
            "champion_id" => 412,
            "champion_name" => "Thresh",
            "image_name" => "Aatrox",
            "title" => "the Chain Warden",
            "tags" => "Support",
            "resource_type" => "Mana",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger,
        ChampionStatsServiceInterface $statBuilder,
        ChampionStatsInterface $stats)
    {
        $this->allRequest = new ChampionRequest(['version' => '7.9.1', 'region' => 'euw']);
        $this->singleRequest = new ChampionRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw']);
        $aatroxStatRequest = new ChampionStatsRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw']);
        $threshStatRequest = new ChampionStatsRequest(['champion_id' => 412, 'version' => '7.9.1', 'region' => 'euw']);
        $adapter->fetch($this->allRequest)->willReturn($this->mockData);
        $adapter->fetch($this->singleRequest)->willReturn([$this->mockData[0]]);
        $statBuilder->fetch($aatroxStatRequest)->willReturn([266 => $stats]);
        $statBuilder->fetch($threshStatRequest)->willReturn([412 => $stats]);
        $this->beConstructedWith($adapter, $logger, $statBuilder);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionServiceInterface');
    }

    public function it_should_fetch_all_if_only_version_passed()
    {
        $this->fetch($this->allRequest)->shouldReturnArrayOfChampions();
    }

    public function it_should_fetch_one_if_version_and_id_passed()
    {
        $this->fetch($this->singleRequest)->shouldReturnArrayOfChampions();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampions' => function($champions) {
                foreach ($champions as $champion) {
                    if (!$champion instanceof Champion) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

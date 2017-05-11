<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use Psr\Log\LoggerInterface;

class JsonChampionsSpec extends ObjectBehavior
{
    private $allRequest;
    private $singleRequest;
    
    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger,
        ChampionStatsServiceInterface $statService,
       $stats)
    {
        $champions = [
            "data" => [
                "Aatrox" => [
                    "id" => 266,
                    "title" => "the Darkin Blade",
                    "name" => "Aatrox",
                    "key" => "Aatrox",
                    "image" => [
                        "full" => "Aatrox.png"
                    ],
                    "partype" => "Blood Well",
                    "tags" => ['Fighter', 'Tank'],
                    "stats" => [
                        "attackrange" => 150,
                        "mpperlevel" => 45,
                        "mp" => 105.6,
                        "attackdamage" => 60.376,
                        "hp" => 537.8,
                        "hpperlevel" => 85,
                        "attackdamageperlevel" => 3.2,
                        "armor" => 24.384,
                        "mpregenperlevel" => 0,
                        "hpregen" => 6.59,
                        "critperlevel" => 0,
                        "spellblockperlevel" => 1.25,
                        "mpregen" => 0,
                        "attackspeedperlevel" => 3,
                        "spellblock" => 32.1,
                        "movespeed" => 345,
                        "attackspeedoffset" => -0.04,
                        "crit" => 0,
                        "hpregenperlevel" => 0.5,
                        "armorperlevel" => 3.8
                    ]
                ],
                "Thresh" => [
                    "id" => 412,
                    "title" => "the Chain Warden",
                    "name" => "Thresh",
                    "key" => "Thresh",
                    "image" => [
                        "full" => "Thresh.png"
                    ],
                    "partype" => "Mana",
                    "tags" => ['Support'],
                    "stats" => [
                        "attackrange" => 150,
                        "mpperlevel" => 45,
                        "mp" => 105.6,
                        "attackdamage" => 60.376,
                        "hp" => 537.8,
                        "hpperlevel" => 85,
                        "attackdamageperlevel" => 3.2,
                        "armor" => 24.384,
                        "mpregenperlevel" => 0,
                        "hpregen" => 6.59,
                        "critperlevel" => 0,
                        "spellblockperlevel" => 1.25,
                        "mpregen" => 0,
                        "attackspeedperlevel" => 3,
                        "spellblock" => 32.1,
                        "movespeed" => 345,
                        "attackspeedoffset" => -0.04,
                        "crit" => 0,
                        "hpregenperlevel" => 0.5,
                        "armorperlevel" => 3.8
                    ],
                ]
            ],
            "version" => '7.4.3'
        ];
        $this->allRequest = new ChampionRequest(['version' => '7.4.3', 'region' => 'euw']);
        $adapter->fetch($this->allRequest)->willReturn($champions);
        $this->singleRequest = new ChampionRequest(['id' => 266, 'region' => 'euw', 'version' => '7.4.3']);
        $adapter->fetch($this->singleRequest)->willReturn($champions['data']['Aatrox']);
        $champions['data']['Aatrox']['version'] = "7.4.3";
        $champions['data']['Aatrox']['region'] = "euw";
        $champions['data']['Thresh']['version'] = "7.4.3";
        $champions['data']['Thresh']['region'] = "euw";
        $stats->beADoubleOf('LeagueOfData\Models\Champion\ChampionStats');
        $statService->create($champions['data']['Aatrox'])->willReturn($stats);
        $statService->create($champions['data']['Thresh'])->willReturn($stats);
        $this->beConstructedWith($adapter, $logger, $statService);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonChampions');
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
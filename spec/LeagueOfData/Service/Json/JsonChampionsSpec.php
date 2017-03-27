<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Models\Champion\Champion;
use Psr\Log\LoggerInterface;

class JsonChampionsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new ChampionRequest(['version' => '7.4.3', 'region' => 'euw']);
        $adapter->fetch($request)->willReturn((object) [
            "data" => (object) [
                "Aatrox" => (object) [
                    "id" => 266,
                    "title" => "the Darkin Blade",
                    "name" => "Aatrox",
                    "key" => "Aatrox",
                    "partype" => "Blood Well",
                    "tags" => ['Fighter', 'Tank'],
                    "stats" => (object) [
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
                "Thresh" => (object) [
                    "id" => 412,
                    "title" => "the Chain Warden",
                    "name" => "Thresh",
                    "key" => "Thresh",
                    "partype" => "Mana",
                    "tags" => ['Support'],
                    "stats" => (object) [
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
                ]
            ],
            "version" => '7.4.3'
        ]);
        $request = new ChampionRequest(['id' => 266, 'region' => 'euw', 'version' => '7.4.3']);
        $adapter->fetch($request)->willReturn((object) [
            "id" => 266,
            "title" => "the Darkin Blade",
            "name" => "Aatrox",
            "key" => "Aatrox",
            "partype" => "Blood Well",
            "tags" => ['Fighter', 'Tank'],
            "stats" => (object) [
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
            "version" => '7.4.3'
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonChampions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionServiceInterface');
    }

    public function it_should_fetch_all_if_only_version_passed()
    {
        $this->fetch('7.4.3')->shouldReturnArrayOfChampions();
    }

    public function it_should_fetch_one_if_version_and_id_passed()
    {
        $this->fetch('7.4.3', 266)->shouldReturnArrayOfChampions();
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
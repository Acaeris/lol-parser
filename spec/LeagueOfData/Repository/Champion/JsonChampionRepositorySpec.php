<?php

namespace spec\LeagueOfData\Repository\Champion;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Champion\ChampionInterface;
use LeagueOfData\Entity\Champion\ChampionStatsInterface;
use LeagueOfData\Entity\Champion\ChampionSpellInterface;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;
use LeagueOfData\Repository\Champion\JsonChampionStatsRepository;
use LeagueOfData\Repository\Champion\JsonChampionSpellRepository;
use LeagueOfData\Repository\Champion\JsonChampionPassiveRepository;

class JsonChampionRepositorySpec extends ObjectBehavior
{
    private $mockData = [
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
                ],
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
                "spells" => [
                    [
                        "id" => 266,
                        "cooldownBurn" => "4",
                        "key" => "Disintegrate",
                        "resource" => "{{ cost }} Mana",
                        "leveltip" => [
                            "effect" => ["{{ e1 }} -> {{ e1NL }}", " {{ cost }} -> {{ costNL }}"],
                            "label" => ["Damage", "Mana Cost"]
                        ],
                        "vars" => [
                            [
                                "coeff" => [0.8],
                                "link" => "spelldamage",
                                "key" => "a1"
                            ]
                        ],
                        "costType" => " Mana",
                        "description" => "Test Description.",
                        "sanitizedDescription" => "Test Sanitised Description.",
                        "sanitizedTooltip" => "Test Sanitised Tooltip.",
                        "effect" => [
                            null,
                            [80, 115, 150, 185, 220],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0]
                        ],
                        "tooltip" => "Test Tooltip.",
                        "maxrank" => 5,
                        "costBurn" => "60/65/70/75/80",
                        "rangeBurn" => "625",
                        "range" => [625, 625, 625, 625, 625],
                        "cost" => [60, 65, 70, 75, 80],
                        "effectBurn" => ["", "80/115/150/185/220", "0", "0", "0", "0", "0", "0", "0", "0", "0"],
                        "image" => [
                            "full" => "Disintegrate.png",
                            "group" => "spell",
                            "sprite" => "spell1.png",
                            "h" => 48,
                            "w" => 48,
                            "y" => 0,
                            "x" => 48
                        ],
                        "cooldown" => [4, 4, 4, 4, 4],
                        "name" => "Disintegrate",
                        "version" => "7.9.1",
                        "region" => "euw",
                        "number" => 0
                    ]
                ],
                "version" => "7.9.1",
                "region" => "euw"
            ]
        ]
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger,
        JsonChampionStatsRepository $statRepository,
        JsonChampionSpellRepository $spellRepository,
        JsonChampionPassiveRepository $passiveRepository,
        ChampionStatsInterface $stats,
        ChampionSpellInterface $spell,
        ChampionPassiveInterface $passive)
    {
        $adapter->fetch()->willReturn($this->mockData);
        $statRepository->create($this->mockData['data']['Aatrox'])->willReturn($stats);
        $passiveRepository->create($this->mockData['data']['Aatrox'])->willReturn($passive);
        $spellRepository->create($this->mockData['data']['Aatrox']['spells'][0])->willReturn($spell);
        $this->beConstructedWith($adapter, $logger, $statRepository, $spellRepository, $passiveRepository);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Champion\JsonChampionRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_fetch_champions(AdapterInterface $adapter)
    {
        $adapter->setOptions(new AnyValuesToken)->willReturn($adapter);
        $this->fetch(["version" => "7.9.1"])->shouldReturnArrayOfChampions();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData['data']['Aatrox'])
            ->shouldImplement('LeagueOfData\Entity\Champion\ChampionInterface');
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampions' => function($champions) {
                foreach ($champions as $champion) {
                    if (!$champion instanceof ChampionInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

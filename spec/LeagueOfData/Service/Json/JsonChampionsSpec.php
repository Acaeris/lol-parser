<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\ChampionInterface;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;

class JsonChampionsSpec extends ObjectBehavior
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
                        "tooltip" => "Deals {{ e1 }} (+{{ a1 }}) magic damage. Mana cost and half the cooldown are refunded if Disintegrate kills the target.",
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
                        "region" => "euw"
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
        RequestInterface $request,
        ChampionStatsServiceInterface $statService,
        ChampionSpellsServiceInterface $spellService,
        ChampionStatsInterface $stats,
        ChampionSpellInterface $spell)
    {
        $request->where()->willReturn(['version' => '7.9.1', 'region' => 'euw']);
        $adapter->fetch($request)->willReturn($this->mockData);
        $statService->create($this->mockData['data']['Aatrox'])->willReturn($stats);
        $spellService->create($this->mockData['data']['Aatrox']['spells'][0])->willReturn($spell);
        $this->beConstructedWith($adapter, $logger, $statService, $spellService);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonChampions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionServiceInterface');
    }

    public function it_should_fetch_champions(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfChampions();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData['data']['Aatrox'])
            ->shouldImplement('LeagueOfData\Models\Interfaces\ChampionInterface');
    }

    public function it_can_add_and_retrieve_champion_objects_from_collection(ChampionInterface $champion)
    {
        $champion->getChampionID()->willReturn(1);
        $this->add([$champion]);
        $this->transfer()->shouldReturnArrayOfChampions();
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
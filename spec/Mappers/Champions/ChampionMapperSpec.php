<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;
use App\Mappers\ImageMapper;
use App\Mappers\Champions\ChampionInfoMapper;
use App\Mappers\Champions\ChampionPassiveMapper;
use App\Mappers\Champions\ChampionRecommendedMapper;
use App\Mappers\Champions\ChampionSkinMapper;
use App\Mappers\Champions\ChampionSpellMapper;
use App\Mappers\Champions\ChampionStatsMapper;
use App\Models\Image;
use App\Models\Champions\ChampionInfo;
use App\Models\Champions\ChampionPassive;
use App\Models\Champions\ChampionRecommended;
use App\Models\Champions\ChampionSkin;
use App\Models\Champions\ChampionSpell;
use App\Models\Champions\ChampionStats;

class ChampionMapperSpec extends ObjectBehavior
{
    private $mockData = [
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
        "info" => [
            "difficulty" => 1,
            "attack" => 2,
            "defense" => 3,
            "magic" => 4
        ],
        "skins" => [
            [
                "id" => 1000,
                "num" => 0,
                "name" => "default"
            ]
        ],
        "recommended" => [
            [
                "champion" => "Annie",
                "title" => "Map12",
                "map" => "HA",
                "blocks" => [
                    [
                        "recMath" => false,
                        "items" => [
                            [
                                "id" => 3112,
                                "count" => 1
                            ]
                        ],
                        "type" => "starting"
                    ]
                ],
                "type" => "riot",
                "mode" => "ARAM"
            ]
        ],
        "lore" => "There have always been those within Noxus who did not agree with the evils perpetrated by the Noxian High Command. The High Command had just put down a coup attempt from the self-proclaimed Crown Prince Raschallion, and a crackdown on any form of dissent against the new government was underway. These political and social outcasts, known as the Gray Order, sought to leave their neighbors in peace as they pursued dark arcane knowledge.<br><br>The leaders of this outcast society were a married couple: Gregori Hastur, the Gray Warlock, and his wife Amoline, the Shadow Witch. Together they led an exodus of magicians and other intelligentsia from Noxus, resettling their followers beyond the Great Barrier to the northern reaches of the unforgiving Voodoo Lands. Though survival was a challenge at times, the Gray Order's colony managed to thrive in a land where so many others would have failed.<br><br>Years after the exodus, Gregori and Amoline had a child: Annie. Early on, Annie's parents knew there was something special about their daughter. At the age of two, Annie miraculously ensorcelled a shadow bear - a ferocious denizen of the petrified forests outside the colony - turning it into her pet. To this day she keeps her bear ''Tibbers'' by her side, often keeping him spellbound as a stuffed doll to be carried like a child's toy. The combination of Annie's lineage and the dark magic of her birthplace have given this little girl tremendous arcane power.",
        "blurb" => "There have always been those within Noxus who did not agree with the evils perpetrated by the Noxian High Command. The High Command had just put down a coup attempt from the self-proclaimed Crown Prince Raschallion, and a crackdown on any form of...",
        "allytips" => [
            "Storing a stun for use with her ultimate can turn the tide of a team fight.",
            "Striking killing blows on minions with Disintegrate enables Annie to farm extremely well early in the game.",
            "Molten Shield is a good spell to cast to work up to Annie's stun, so sometimes it's beneficial to grab at least 1 rank in it early."
        ],
        "enemytips" => [
            "Annie's summoned bear, Tibbers, burns opposing units around himself. Try to keep your distance from him after he's been summoned.",
            "Summoner Smite can be used to help take down Tibbers.",
            "Keep an eye out for a white, swirling power around Annie. It means she's ready to unleash her stun."
        ],
    ];

    public function let(
        ChampionInfoMapper $infoMapper,
        ChampionPassiveMapper $passiveMapper,
        ChampionRecommendedMapper $recommendedMapper,
        ChampionSkinMapper $skinMapper,
        ChampionSpellMapper $spellMapper,
        ChampionStatsMapper $statsMapper,
        ImageMapper $imageMapper
    ) {
        $this->beConstructedWith(
            $infoMapper,
            $statsMapper,
            $imageMapper,
            $skinMapper,
            $passiveMapper,
            $recommendedMapper,
            $spellMapper
        );
    }

    public function it_can_map_json_data(
        ChampionInfoMapper $infoMapper,
        ChampionPassiveMapper $passiveMapper,
        ChampionRecommendedMapper $recommendedMapper,
        ChampionSkinMapper $skinMapper,
        ChampionSpellMapper $spellMapper,
        ChampionStatsMapper $statsMapper,
        ImageMapper $imageMapper,
        ChampionInfo $info,
        ChampionPassive $passive,
        ChampionRecommended $recommended,
        ChampionSkin $skin,
        ChampionSpell $spell,
        ChampionStats $stats,
        Image $image
    ) {
        $infoMapper->mapFromArray($this->mockData['info'])
            ->willReturn($info);
        $statsMapper->mapFromArray($this->mockData['stats'])
            ->willReturn($stats);
        $imageMapper->mapFromArray($this->mockData['image'])
            ->willReturn($image);
        $skinMapper->mapFromArray($this->mockData['skins'][0])
            ->willReturn($skin);
        $passiveMapper->mapFromArray($this->mockData['passive'])
            ->willReturn($passive);
        $recommendedMapper->mapFromArray($this->mockData['recommended'][0])
            ->willReturn($recommended);
        $spellMapper->mapFromArray($this->mockData['spells'][0])
            ->willReturn($spell);

        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\Champion');
    }
}

<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;
use App\Mappers\Spells\SpellLevelTipMapper;
use App\Mappers\Spells\SpellVarMapper;
use App\Mappers\ImageMapper;
use App\Models\Spells\SpellLevelTip;
use App\Models\Spells\SpellVar;
use App\Models\Image;

class ChampionSpellMapperSpec extends ObjectBehavior
{
    private $mockData = [
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
    ];

    public function let(
        SpellLevelTipMapper $spellLevelTipMapper,
        SpellVarMapper $spellVarMapper,
        ImageMapper $imageMapper
    ) {
        $this->beConstructedWith(
            $spellLevelTipMapper,
            $spellVarMapper,
            $imageMapper
        );
    }

    public function it_can_map_array_data(
        SpellLevelTipMapper $spellLevelTipMapper,
        SpellVarMapper $spellVarMapper,
        ImageMapper $imageMapper,
        SpellLevelTip $spellLevelTip,
        SpellVar $spellVar,
        Image $image
    ) {
        $spellLevelTipMapper->mapFromArray($this->mockData['leveltip'])
            ->willReturn($spellLevelTip);
        $spellVarMapper->mapFromArray($this->mockData['vars'][0])
            ->willReturn($spellVar);
        $imageMapper->mapFromArray($this->mockData['image'])
            ->willReturn($image);
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionSpell');
    }
}

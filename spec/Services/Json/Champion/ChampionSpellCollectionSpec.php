<?php

namespace spec\App\Services\Json\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use App\Adapters\AdapterInterface;

class ChampionSpellCollectionSpec extends ObjectBehavior
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

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('App\Services\Json\Champion\ChampionSpellCollection');
        $this->shouldImplement('App\Services\FetchServiceInterface');
    }

    public function it_can_convert_data_to_spell_object()
    {
        $this->create($this->mockData)->shouldImplement('App\Models\Champion\ChampionSpellInterface');
    }
}

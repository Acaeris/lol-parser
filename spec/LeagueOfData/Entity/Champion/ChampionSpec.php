<?php

namespace spec\LeagueOfData\Entity\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Entity\Champion\ChampionStatsInterface;
use LeagueOfData\Entity\Champion\ChampionSpellInterface;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;

class ChampionSpec extends ObjectBehavior
{
    public function let(
        ChampionStatsInterface $stats,
        ChampionSpellInterface $spell,
        ChampionPassiveInterface $passive
    ) {
        $this->beConstructedWith(
            1, // Champion ID
            "Test", // Champion Name
            "Test Character", // Champion Title
            "mp", // Resource Type
            ["Fighter", "Mage"], // Tags
            $stats, // Stats
            $passive, // Passive
            [$spell], // Spells
            "Test", // Image Name
            "6.21.1", // Version
            "euw" // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Champion\Champion');
        $this->shouldImplement('LeagueOfData\Entity\Champion\ChampionInterface');
        $this->shouldImplement('LeagueOfData\Entity\EntityInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
        $this->shouldThrow('LeagueOfData\Library\Immutable\ImmutableException')
            ->during('__set', ['id', 1]);
    }

    public function it_can_return_key_data_for_indexing()
    {
        $this->getKeyData()->shouldReturn(
            [
            'champion_id' => 1,
            'version' => '6.21.1',
            'region' => 'euw'
            ]
        );
    }

    public function it_has_all_data_available()
    {
        $this->getChampionID()->shouldReturn(1);
        $this->getName()->shouldReturn("Test");
        $this->getTitle()->shouldReturn("Test Character");
        $this->getImageName()->shouldReturn("Test");
        $this->getResourceType()->shouldReturn('mp');
        $this->getVersion()->shouldReturn('6.21.1');
        $this->getTags()->shouldReturn(['Fighter', 'Mage']);
        $this->getStats()->shouldReturnAnInstanceOf('LeagueOfData\Entity\Champion\ChampionStatsInterface');
        $this->getPassive()->shouldReturnAnInstanceOf('LeagueOfData\Entity\Champion\ChampionPassiveInterface');
        $this->getSpells()->shouldReturnArrayOfSpells();
        $this->getRegion()->shouldReturn('euw');
    }

    public function it_can_return_tags_as_simple_string()
    {
        $this->getTagsAsString()->shouldReturn("Fighter|Mage");
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfSpells' => function ($spells) {
                foreach ($spells as $spell) {
                    if (!$spell instanceof ChampionSpellInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

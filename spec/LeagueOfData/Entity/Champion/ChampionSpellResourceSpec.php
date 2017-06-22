<?php

namespace spec\LeagueOfData\Entity\Champion;

use PhpSpec\ObjectBehavior;

class ChampionSpellResourceSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            "Mana", // Name
            [60, 65, 70, 75, 80] // Values
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Champion\ChampionSpellResource');
        $this->shouldImplement('LeagueOfData\Entity\Champion\ChampionSpellResourceInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data_available()
    {
        $this->getName()->shouldReturn("Mana");
        $this->getValues()->shouldReturn([60, 65, 70, 75, 80]);
    }

    public function it_can_fetch_value_by_rank()
    {
        $this->getValueByRank(3)->shouldReturn(70);
    }

    public function it_throws_an_exception_when_rank_too_high()
    {
        $this->shouldThrow('InvalidArgumentException')->during('getValueByRank', [6]);
    }
}

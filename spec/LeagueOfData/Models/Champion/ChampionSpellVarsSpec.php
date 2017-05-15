<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionSpellVarsSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            "spelldamage", // Link
            [0.8, 0.9, 1.0, 1.1, 1.2], // Coeff
            "a1" // Key
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionSpellVars');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionSpellVarsInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data_available()
    {
        $this->getLink()->shouldReturn("spelldamage");
        $this->getCoeff()->shouldReturn([0.8, 0.9, 1.0, 1.1, 1.2]);
        $this->getKey()->shouldReturn("a1");
    }

    public function it_can_fetch_coeff_by_rank()
    {
        $this->getCoeffByRank(3)->shouldReturn(1.0);
    }

    public function it_should_throw_an_exception_if_rank_beyond_limit()
    {
        $this->shouldThrow('InvalidArgumentException')->during('getCoeffByRank', [6]);
    }
}

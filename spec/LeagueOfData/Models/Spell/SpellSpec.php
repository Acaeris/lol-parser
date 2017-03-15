<?php

namespace spec\LeagueOfData\Models\Spell;

use PhpSpec\ObjectBehavior;

class SpellSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1, 'Test Spell', 'The spell does a thing.', 5,
            [10, 9, 8, 7, 6], [100, 90, 80, 70, 60]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Spell\Spell');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\SpellInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_has_an_id()
    {
        $this->getID()->shouldReturn(1);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn('Test Spell');
    }

    function it_has_a_desccription()
    {
        $this->description()->shouldReturn('The spell does a thing.');
    }

    function it_has_cooldowns()
    {
        $this->cooldowns()->shouldReturn([10, 9, 8, 7, 6]);
    }

    function it_can_return_cooldown_for_a_given_rank()
    {
        $this->cooldownByRank(3)->shouldReturn(8);
    }

    function it_has_costs()
    {
        $this->costs()->shouldReturn([100, 90, 80, 70, 60]);
    }

    function it_can_return_cost_for_a_given_rank()
    {
        $this->costByRank(3)->shouldReturn(80);
    }

    function it_has_a_max_rank()
    {
        $this->maxRank()->shouldReturn(5);
    }

    function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => 'Test Spell',
            'description' => 'The spell does a thing.',
            'maxRank' => 5,
            'cooldowns' => '10|9|8|7|6',
            'costs' => '100|90|80|70|60'
        ]);
    }
}

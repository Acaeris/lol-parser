<?php

namespace spec\LeagueOfData\Models\Spell;

use PhpSpec\ObjectBehavior;

class SpellResourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('mp', 60, 5);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Spell\SpellResource');
    }

    function it_is_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_is_a_resource()
    {
        $this->shouldHaveType('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    function it_can_output_a_type_of_resource()
    {
        $this->type()->shouldReturn('mp');
    }

    function it_can_output_a_base_value()
    {
        $this->baseValue()->shouldReturn(60.0);
    }

    function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(5.0);
    }

    function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(80.0);
    }

    function it_can_output_the_data_as_an_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'mp' => 60.0,
            'mpPerLevel' => 5.0
        ]);
    }
}
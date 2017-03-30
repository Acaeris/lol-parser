<?php

namespace spec\LeagueOfData\Models\Item;

use PhpSpec\ObjectBehavior;

class ItemStatSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('moveSpeed', 30);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Item\ItemStat');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\StatInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_a_stat_key()
    {
        $this->key()->shouldReturn('moveSpeed');
    }

    public function it_has_a_stat_value()
    {
        $this->value()->shouldReturn((float) 30);
    }

    public function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn(['moveSpeed' => (float) 30]);
    }
}

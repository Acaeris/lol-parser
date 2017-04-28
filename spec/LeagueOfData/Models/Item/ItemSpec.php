<?php

namespace spec\LeagueOfData\Models\Item;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\StatInterface;

class ItemSpec extends ObjectBehavior
{
    public function let(StatInterface $itemStat)
    {
        $itemStat->key()->willReturn('moveSpeed');
        $itemStat->value()->willReturn((float) 30);
        $this->beConstructedWith(1, 'Infinity Edge', 'Test Description', 300, 210, [$itemStat], '7.4.3', 'euw');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Item\Item');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ItemInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getID()->shouldReturn(1);
        $this->name()->shouldReturn('Infinity Edge');
        $this->description()->shouldReturn('Test Description');
        $this->goldToBuy()->shouldReturn(300);
        $this->goldFromSale()->shouldReturn(210);
        $this->version()->shouldReturn('7.4.3');
        $this->stats()->shouldReturn(['moveSpeed' => (float) 30]);
        $this->region()->shouldReturn('euw');
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('moveSpeed')->shouldReturn((float) 30);
    }
}

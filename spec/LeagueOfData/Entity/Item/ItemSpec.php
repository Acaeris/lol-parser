<?php

namespace spec\LeagueOfData\Entity\Item;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Entity\StatInterface;

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
        $this->shouldHaveType('LeagueOfData\Entity\Item\Item');
        $this->shouldImplement('LeagueOfData\Entity\Item\ItemInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getItemID()->shouldReturn(1);
        $this->getName()->shouldReturn('Infinity Edge');
        $this->getDescription()->shouldReturn('Test Description');
        $this->getGoldToBuy()->shouldReturn(300);
        $this->getGoldFromSale()->shouldReturn(210);
        $this->getStats()->shouldReturn(['moveSpeed' => (float) 30]);
        $this->getVersion()->shouldReturn('7.4.3');
        $this->getRegion()->shouldReturn('euw');
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('moveSpeed')->shouldReturn((float) 30);
    }
}

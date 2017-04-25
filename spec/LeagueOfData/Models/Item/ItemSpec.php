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

    public function it_has_an_id()
    {
        $this->getID()->shouldReturn(1);
    }

    public function it_has_a_name()
    {
        $this->name()->shouldReturn('Infinity Edge');
    }

    public function it_has_a_description()
    {
        $this->description()->shouldReturn('Test Description');
    }

    public function it_has_a_gold_purchase_value()
    {
        $this->goldToBuy()->shouldReturn(300);
    }

    public function it_has_a_gold_sale_value()
    {
        $this->goldFromSale()->shouldReturn(210);
    }

    public function it_has_a_version_number()
    {
        $this->version()->shouldReturn('7.4.3');
    }

    public function it_has_item_stats()
    {
        $this->stats()->shouldReturn(['moveSpeed' => (float) 30]);
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('moveSpeed')->shouldReturn((float) 30);
    }

    public function it_has_a_region()
    {
        $this->region()->shouldReturn('euw');
    }
}

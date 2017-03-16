<?php

namespace spec\LeagueOfData\Models\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1, 'Infinity Edge', 'Test Description', 300, 210);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Item\Item');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ItemInterface');
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
        $this->name()->shouldReturn('Infinity Edge');
    }

    function it_has_a_description()
    {
        $this->description()->shouldReturn('Test Description');
    }

    function it_has_a_gold_purchase_value()
    {
        $this->goldToBuy()->shouldReturn(300);
    }

    function it_has_a_gold_sale_value()
    {
        $this->goldFromSale()->shouldReturn(210);
    }

    function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => 'Infinity Edge',
            'description' => 'Test Description',
            'purchaseValue' => 300,
            'saleValue' => 210
        ]);
    }
}

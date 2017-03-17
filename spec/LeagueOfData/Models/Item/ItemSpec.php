<?php

namespace spec\LeagueOfData\Models\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(1, 'Infinity Edge', 'Test Description', 300, 210, '7.4.3');
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

    public function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => 'Infinity Edge',
            'description' => 'Test Description',
            'purchaseValue' => 300,
            'saleValue' => 210,
            'version' => '7.4.3'
        ]);
    }
}

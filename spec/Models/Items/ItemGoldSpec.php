<?php

namespace spec\App\Models\Items;

use PhpSpec\ObjectBehavior;

class ItemGoldSpec extends ObjectBehavior
{
    private $sell = 1;
    private $base = 2;
    private $total = 3;
    private $purchasable = true;

    public function let()
    {
        $this->beConstructedWith(
            $this->base,
            $this->total,
            $this->sell,
            $this->purchasable
        );
    }

    public function it_has_a_base_value()
    {
        $this->getBaseValue()->shouldReturn($this->base);
    }

    public function it_has_a_total_value()
    {
        $this->getTotalValue()->shouldReturn($this->total);
    }

    public function it_has_a_sell_value()
    {
        $this->getSellValue()->shouldReturn($this->sell);
    }

    public function it_has_if_item_is_purchasable()
    {
        $this->isPurchasable()->shouldReturn($this->purchasable);
    }
}

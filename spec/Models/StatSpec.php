<?php

namespace spec\App\Models;

use PhpSpec\ObjectBehavior;

class StatSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('Test', 1.1);
    }

    public function it_has_a_stat_name()
    {
        $this->getStatName()->shouldReturn('Test');
    }

    public function it_has_a_stat_modifier()
    {
        $this->getStatModifier()->shouldReturn(1.1);
    }
}
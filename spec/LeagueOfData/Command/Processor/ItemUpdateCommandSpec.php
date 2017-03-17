<?php

namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;

class ItemUpdateCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\ItemUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }
}

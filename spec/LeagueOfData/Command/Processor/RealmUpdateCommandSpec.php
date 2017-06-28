<?php

namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;

class RealmUpdateCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\RealmUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:realm');
    }
}

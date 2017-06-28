<?php
namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;

class MasteryUpdateCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\MasteryUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:mastery');
    }
}

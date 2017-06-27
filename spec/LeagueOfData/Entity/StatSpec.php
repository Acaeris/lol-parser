<?php
namespace spec\LeagueOfData\Entity;

use PhpSpec\ObjectBehavior;

class StatSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            "FlatPhysicalDamageMod", // Stat Name
            0.525 // Stat Modifier
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Stat');
        $this->shouldImplement('LeagueOfData\Entity\StatInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getStatName()->shouldReturn("FlatPhysicalDamageMod");
        $this->getStatModifier()->shouldReturn(0.525);
    }
}

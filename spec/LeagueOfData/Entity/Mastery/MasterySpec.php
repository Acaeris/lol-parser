<?php
namespace spec\LeagueOfData\Entity\Mastery;

use PhpSpec\ObjectBehavior;

class MasterySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            6111, // Mastery ID
            "Fury", // Mastery Name
            ["+0.8% Attack Speed", "+1.6% Attack Speed", "+2.4% Attack Speed", "+3.2% Attack Speed",
                "+4% Attack Speed"], // Description
            5, // Ranks
            "6111.png", // Image Name
            "Ferocity", // Mastery Tree
            "7.9.1", // Version
            "euw" // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Mastery\Mastery');
        $this->shouldImplement('LeagueOfData\Entity\Mastery\MasteryInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getMasteryID()->shouldReturn(6111);
        $this->getName()->shouldReturn("Fury");
        $this->getDescription()->shouldReturn(["+0.8% Attack Speed", "+1.6% Attack Speed", "+2.4% Attack Speed",
            "+3.2% Attack Speed", "+4% Attack Speed"]);
        $this->getRanks()->shouldReturn(5);
        $this->getImageName()->shouldReturn("6111.png");
        $this->getMasteryTree()->shouldReturn("Ferocity");
        $this->getVersion()->shouldReturn("7.9.1");
        $this->getRegion()->shouldReturn("euw");
    }
}

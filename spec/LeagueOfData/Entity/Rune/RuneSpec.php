<?php
namespace spec\LeagueOfData\Entity\Rune;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Entity\StatInterface;

class RuneSpec extends ObjectBehavior
{
    public function let(StatInterface $stat)
    {
        $stat->getStatName()->willReturn('physicalAttackMod');
        $stat->getStatModifier()->willReturn(0.525);
        $this->beConstructedWith(
            1, // Rune ID
            "Lesser Mark of Attack Damage", // Rune Name
            "+0.53 attack damage", // Description
            "r_1_1.png", // Image Name
            [$stat], // Rune Stats
            ["physicalAttack", "flat", "mark"], // Rune Tags
            "7.9.1", // Version
            "euw" // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Rune\Rune');
        $this->shouldImplement('LeagueOfData\Entity\Rune\RuneInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getRuneID()->shouldReturn(1);
        $this->getName()->shouldReturn("Lesser Mark of Attack Damage");
        $this->getDescription()->shouldReturn("+0.53 attack damage");
        $this->getImageName()->shouldReturn("r_1_1.png");
        $this->getStats()->shouldReturnArrayOfStats();
        $this->getTags()->shouldReturn(["physicalAttack", "flat", "mark"]);
        $this->getVersion()->shouldReturn("7.9.1");
        $this->getRegion()->shouldReturn("euw");
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('physicalAttackMod')->shouldReturn(0.525);
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfStats' => function(array $stats) : bool {
                foreach ($stats as $stat) {
                    if (!$stat instanceof StatInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}

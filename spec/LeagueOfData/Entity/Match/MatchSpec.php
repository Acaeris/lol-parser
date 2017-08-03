<?php

namespace spec\LeagueOfData\Entity\Match;

use PhpSpec\ObjectBehavior;

class MatchSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Match ID
            "ASSASSINATE", // Game Mode
            "MATCHED_GAME", // Game Type
            865, // Game Duration
            "7.12.190.9002", // Game Version
            "euw" // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Match\Match');
        $this->shouldImplement('LeagueOfData\Entity\Match\MatchInterface');
        $this->shouldImplement('LeagueOfData\Entity\EntityInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
        $this->shouldThrow('LeagueOfData\Library\Immutable\ImmutableException')
            ->during('__set', ['id', 1]);
    }

    public function it_can_return_key_data_for_indexing()
    {
        $this->getKeyData()->shouldReturn([
            'match_id' => 1,
            'region' => "euw"
        ]);
    }

    public function it_has_all_data_available()
    {
        $this->getMatchID()->shouldReturn(1);
        $this->getRegion()->shouldReturn("euw");
        $this->getMode()->shouldReturn("ASSASSINATE");
        $this->getType()->shouldReturn("MATCHED_GAME");
        $this->getDuration()->shouldReturn(865);
        $this->getVersion()->shouldReturn('7.12.190.9002');
    }
}

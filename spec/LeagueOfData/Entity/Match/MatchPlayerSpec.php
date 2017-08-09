<?php

namespace spec\LeagueOfData\Entity\Match;

use PhpSpec\ObjectBehavior;

class MatchPlayerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Match ID
            2, // Participant ID
            3, // Account ID
            4, // Champion ID
            'euw' // Version
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Match\MatchPlayer');
        $this->shouldImplement('LeagueOfData\Entity\Match\MatchPlayerInterface');
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
        $this->getKeyData()->shouldReturn(
            [
                'match_id' => 1,
                'participant_id' => 2,
                'account_id' => 3,
                'region' => 'euw'
            ]
        );
    }

    public function it_has_all_data_available()
    {
        $this->getMatchID()->shouldReturn(1);
        $this->getParticipantID()->shouldReturn(2);
        $this->getAccountID()->shouldReturn(3);
        $this->getChampionID()->shouldReturn(4);
        $this->getRegion()->shouldReturn("euw");
    }
}

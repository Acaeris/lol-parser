<?php

namespace spec\App\Models\Participants;

use PhpSpec\ObjectBehavior;
use App\Models\Players\Player;

class ParticipantIdentitySpec extends ObjectBehavior
{
    private $participantId = 1;

    public function let(Player $player)
    {
        $this->beConstructedWith($this->participantId, $player);
    }

    public function it_has_a_participant_id()
    {
        $this->getParticipantId()->shouldReturn($this->participantId);
    }

    public function it_has_a_player_object(Player $player)
    {
        $this->getPlayer()->shouldReturn($player);
    }
}

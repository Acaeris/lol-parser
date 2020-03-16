<?php

namespace App\Models\Participants;

use App\Models\Players\Player;

class ParticipantIdentity
{
    /* @var int Participant ID */
    private $participantId;

    /* @var Player Player */
    private $player;

    /**
     * @param int $participantId - Participant ID
     * @param Player $player - Player
     */
    public function __construct(int $participantId, Player $player)
    {
        $this->participantId = $participantId;
        $this->player = $player;
    }

    /**
     * @return int - Participant ID
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @return Player - Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }
}

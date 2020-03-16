<?php

namespace spec\App\Models\Matches;

use App\Models\Matches\TeamStats;
use App\Models\Participants\Participant;
use App\Models\Participants\ParticipantIdentity;
use PhpSpec\ObjectBehavior;

class MatchSpec extends ObjectBehavior
{
    private $gameId = 1;
    private $seasonId = 2;
    private $platformId = 3;
    private $queueId = 4;
    private $mapId = 5;
    private $gameMode = 'Arcade';
    private $gameType = 'ARAM';
    private $participantIdentities = [];
    private $participants = [];
    private $teams = [];
    private $gameCreation = 6;
    private $gameDuration = 7;
    private $gameVersion = '10.1.2';

    public function let(ParticipantIdentity $participantIdentity, Participant $participant, TeamStats $team)
    {
        $this->participantIdentities[] = $participantIdentity;
        $this->participants[] = $participant;
        $this->teams[] = $team;

        $this->beConstructedWith(
            $this->gameId,
            $this->seasonId,
            $this->platformId,
            $this->queueId,
            $this->mapId,
            $this->gameMode,
            $this->gameType,
            $this->participantIdentities,
            $this->participants,
            $this->teams,
            $this->gameCreation,
            $this->gameDuration,
            $this->gameVersion
        );
    }

    public function it_has_a_game_id()
    {
        $this->getGameId()->shouldReturn($this->gameId);
    }

    public function it_has_a_season_id()
    {
        $this->getSeasonId()->shouldReturn($this->seasonId);
    }

    public function it_has_a_platform_id()
    {
        $this->getPlatformId()->shouldReturn($this->platformId);
    }

    public function it_has_a_queue_id()
    {
        $this->getQueueId()->shouldReturn($this->queueId);
    }

    public function it_has_a_map_id()
    {
        $this->getMapId()->shouldReturn($this->mapId);
    }

    public function it_has_a_game_mode()
    {
        $this->getGameMode()->shouldReturn($this->gameMode);
    }

    public function it_has_a_game_type()
    {
        $this->getGameType()->shouldReturn($this->gameType);
    }

    public function it_has_participant_identities()
    {
        $this->getParticipantIdentities()->shouldReturn($this->participantIdentities);
    }

    public function it_has_participants()
    {
        $this->getParticipants()->shouldReturn($this->participants);
    }

    public function it_has_teams()
    {
        $this->getTeams()->shouldReturn($this->teams);
    }

    public function it_has_a_creation_time()
    {
        $this->getCreation()->shouldReturn($this->gameCreation);
    }

    public function it_has_a_duration_time()
    {
        $this->getDuration()->shouldReturn($this->gameDuration);
    }

    public function it_has_a_game_version()
    {
        $this->getGameVersion()->shouldReturn($this->gameVersion);
    }
}

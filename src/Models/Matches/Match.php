<?php

namespace App\Models\Matches;

use App\Models\Participants\Participant;
use App\Models\Participants\ParticipantIdentity;

class Match
{
    /* @var int Game ID */
    private $gameId;

    /* @var int Season ID */
    private $seasonId;

    /* @var int Platform ID */
    private $platformId;

    /* @var int Queue ID */
    private $queueId;

    /* @var int Map ID */
    private $mapId;

    /* @var string Game mode */
    private $gameMode;

    /* @var string Game type */
    private $gameType;

    /* @var ParticipantIdentity[] Participant identities */
    private $participantIdentities;

    /* @var Participant[] Participants */
    private $participants;

    /* @var TeamStats[] Teams */
    private $teams;

    /* @var int Game creation */
    private $gameCreation;

    /* @var int Game duration */
    private $gameDuration;

    /* @var string Game version */
    private $gameVersion;

    /**
     * @param int $gameId - Game ID
     * @param int $seasonId - Season ID
     * @param int $platformId - Platform ID
     * @param int $queueId - Queue ID
     * @param int $mapId - Map ID
     * @param string $gameMode - Game mode
     * @param string $gameType - Game type
     * @param ParticipantIdentity[] $participantIdentities - Participant identities
     * @param Participant[] $participants - Participants
     * @param TeamStats[] $teams - Teams
     * @param int $gameCreation - Game creation
     * @param int $gameDuration - Game duration
     * @param string $gameVersion - Game version
     * @throws \Exception
     */
    public function __construct(
        int $gameId,
        int $seasonId,
        int $platformId,
        int $queueId,
        int $mapId,
        string $gameMode,
        string $gameType,
        array $participantIdentities,
        array $participants,
        array $teams,
        int $gameCreation,
        int $gameDuration,
        string $gameVersion
    ) {
        foreach ($participantIdentities as $participantIdentity) {
            if (!$participantIdentity instanceof ParticipantIdentity) {
                throw new \Exception('Invalid Participant Identity in match ' . $gameId);
            }
        }

        foreach ($participants as $participant) {
            if (!$participant instanceof Participant) {
                throw new \Exception('Invalid Participant in match ' . $gameId);
            }
        }

        foreach ($teams as $team) {
            if (!$team instanceof TeamStats) {
                throw new \Exception('Invalid Team in match ' . $gameId);
            }
        }

        $this->gameId = $gameId;
        $this->seasonId = $seasonId;
        $this->platformId = $platformId;
        $this->queueId = $queueId;
        $this->mapId = $mapId;
        $this->gameMode = $gameMode;
        $this->gameType = $gameType;
        $this->participantIdentities = $participantIdentities;
        $this->participants = $participants;
        $this->teams = $teams;
        $this->gameCreation = $gameCreation;
        $this->gameDuration = $gameDuration;
        $this->gameVersion = $gameVersion;
    }

    /**
     * @return int - Game ID
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return int - Season ID
     */
    public function getSeasonId(): int
    {
        return $this->seasonId;
    }

    /**
     * @return int - Platform ID
     */
    public function getPlatformId(): int
    {
        return $this->platformId;
    }

    /**
     * @return int - Queue ID
     */
    public function getQueueId(): int
    {
        return $this->queueId;
    }

    /**
     * @return int - Map ID
     */
    public function getMapId(): int
    {
        return $this->mapId;
    }

    /**
     * @return string - Game mode
     */
    public function getGameMode(): string
    {
        return $this->gameMode;
    }

    /**
     * @return string - Game type
     */
    public function getGameType(): string
    {
        return $this->gameType;
    }

    /**
     * @return ParticipantIdentity[] - Participant identities
     */
    public function getParticipantIdentities(): array
    {
        return $this->participantIdentities;
    }

    /**
     * @return Participant[] - Participant
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @return TeamStats[] - Teams
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return int - Game creation
     */
    public function getCreation(): int
    {
        return $this->gameCreation;
    }

    /**
     * @return int - Game duration
     */
    public function getDuration(): int
    {
        return $this->gameDuration;
    }

    /**
     * @return string - Game version
     */
    public function getGameVersion(): string
    {
        return $this->gameVersion;
    }
}

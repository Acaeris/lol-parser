<?php

namespace App\Models\Matches;

class TeamStats
{
    /* @var int Team ID */
    private $teamId;

    /* @var TeamBan[] Bans */
    private $bans;

    /* @var bool First blood */
    private $firstBlood;

    /* @var bool First rift herald */
    private $firstRiftHerald;

    /* @var bool First Dragon Kill */
    private $firstDragon;

    /* @var bool First baron */
    private $firstBaron;

    /* @var bool First tower */
    private $firstTower;

    /* @var bool First tower */
    private $firstInhibitor;

    /* @var int Dragon kills */
    private $dragonKills;

    /* @var int Rift herald kills */
    private $riftHeraldKills;

    /* @var int Baron kills */
    private $baronKills;

    /* @var int Vilemaw kills */
    private $vilemawKills;

    /* @var int Tower kills */
    private $towerKills;

    /* @var int Inhibitor kills */
    private $inhibitorKills;

    /* @var int Dominion victory score */
    private $dominionVictoryScore;

    /* @var string Win */
    private $win;

    /**
     * @param int $teamId Team ID
     * @param TeamBan[] $bans Bans
     * @param bool $firstBlood First blood
     * @param bool $firstDragon First dragon
     * @param bool $firstRiftHerald First rift herald
     * @param bool $firstBaron First baron
     * @param bool $firstTower First tower
     * @param bool $firstInhibitor First inhibitor
     * @param int $dragonKills Dragon kills
     * @param int $riftHeraldKills Rift herald kills
     * @param int $baronKills Baron kills
     * @param int $vilemawKills Vilemaw kills
     * @param int $towerKills Tower kills
     * @param int $inhibitorKills Inhibitor kills
     * @param int $dominionVictoryScore Dominion victory score
     * @param string $win Win
     * @throws \Exception
     */
    public function beConstrcutedWith(
        int $teamId,
        array $bans,
        bool $firstBlood,
        bool $firstDragon,
        bool $firstRiftHerald,
        bool $firstBaron,
        bool $firstTower,
        bool $firstInhibitor,
        int $dragonKills,
        int $riftHeraldKills,
        int $baronKills,
        int $vilemawKills,
        int $towerKills,
        int $inhibitorKills,
        int $dominionVictoryScore,
        string $win
    ) {
        foreach ($bans as $ban) {
            if (!$ban instanceof TeamBan) {
                throw new \Exception('Invalid team ban for team ' . $teamId);
            }
        }

        $this->teamId = $teamId;
        $this->bans = $bans;
        $this->firstBlood = $firstBlood;
        $this->firstDragon = $firstDragon;
        $this->firstRiftHerald = $firstRiftHerald;
        $this->firstBaron = $firstBaron;
        $this->firstTower = $firstTower;
        $this->firstInhibitor = $firstInhibitor;
        $this->dragonKills = $dragonKills;
        $this->riftHeraldKills = $riftHeraldKills;
        $this->baronKills = $baronKills;
        $this->vilemawKills = $vilemawKills;
        $this->towerKills = $towerKills;
        $this->inhibitorKills = $inhibitorKills;
        $this->dominionVictoryScore = $dominionVictoryScore;
        $this->win = $win;
    }

    /**
     * @return int Team ID
     */
    public function getTeamID(): int
    {
        return $this->teamId;
    }

    /**
     * @return TeamBans[] Bans
     */
    public function getBans(): array
    {
        return $this->bans;
    }

    /**
     * @return bool First blood
     */
    public function hadFirstBlood(): bool
    {
        return $this->firstBlood;
    }

    /**
     * @return bool First dragon
     */
    public function hadFirstDragon(): bool
    {
        return $this->firstDragon;
    }

    /**
     * @return bool First rift herald
     */
    public function hadFirstRiftHerald(): bool
    {
        return $this->firstRiftHerald;
    }

    /**
     * @return bool First baron
     */
    public function hadFirstBaron(): bool
    {
        return $this->firstBaron;
    }

    /**
     * @return bool First tower
     */
    public function hadFirstTower(): bool
    {
        return $this->firstTower;
    }

    /**
     * @return bool First inhibitor
     */
    public function hadFirstInhibitor(): bool
    {
        return $this->firstInhibitor;
    }

    /**
     * @return int Dragon kills
     */
    public function getDragonKills(): int
    {
        return $this->dragonKills;
    }

    /**
     * @return int Rift Herald kills
     */
    public function getRiftHeraldKills(): int
    {
        return $this->riftHeraldKills;
    }

    /**
     * @return int Baron kills
     */
    public function getBaronKills(): int
    {
        return $this->baronKills;
    }

    /**
     * @return int Vilemaw kills
     */
    public function getVilemawKills(): int
    {
        return $this->vilemawKills;
    }

    /**
     * @return int Tower kills
     */
    public function getTowerKills(): int
    {
        return $this->towerKills;
    }

    /**
     * @return int Inhibitor kills
     */
    public function getInhibitorKills(): int
    {
        return $this->inhibitorKills;
    }

    /**
     * @return int Dominion victory score
     */
    public function getDominionVictoryScore(): int
    {
        return $this->dominionVictoryScore;
    }

    /**
     * @return string Win??
     */
    public function getWin(): string
    {
        return $this->win;
    }
}

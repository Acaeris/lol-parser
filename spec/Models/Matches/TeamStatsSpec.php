<?php

namespace spec\App\Models\Matches;

use App\Models\Matches\TeamBan;
use PhpSpec\ObjectBehavior;

class TeamStatsSpec extends ObjectBehavior
{
    private $teamId = 1;
    private $bans = [];
    private $firstBlood = false;
    private $firstDragon = true;
    private $firstRiftHerald = false;
    private $firstBaron = true;
    private $firstTower = false;
    private $firstInhibitor = true;
    private $dragonKills = 2;
    private $riftHeraldKills = 3;
    private $baronKills = 4;
    private $vilemawKills = 5;
    private $towerKills = 6;
    private $inhibitorKills = 7;
    private $dominionVictoryScore = 8;
    private $win = 'Victory';

    public function let(TeamBan $ban)
    {
        $this->bans = [$ban];

        $this->beConstrcutedWith(
            $this->teamId,
            $this->bans,
            $this->firstBlood,
            $this->firstDragon,
            $this->firstRiftHerald,
            $this->firstBaron,
            $this->firstTower,
            $this->firstInhibitor,
            $this->dragonKills,
            $this->riftHeraldKills,
            $this->baronKills,
            $this->vilemawKills,
            $this->towerKills,
            $this->inhibitorKills,
            $this->dominionVictoryScore,
            $this->win
        );
    }

    public function it_has_a_team_id()
    {
        $this->getTeamID()->shouldReturn($this->teamId);
    }

    public function it_has_bans()
    {
        $this->getBans()->shouldReturn($this->bans);
    }

    public function it_has_if_team_scored_first_blood()
    {
        $this->hadFirstBlood()->shouldReturn($this->firstBlood);
    }

    public function it_has_if_team_scored_first_dragon()
    {
        $this->hadFirstDragon()->shouldReturn($this->firstDragon);
    }

    public function it_has_if_team_scored_first_rift_herald()
    {
        $this->hadFirstRiftHerald()->shouldReturn($this->firstRiftHerald);
    }

    public function it_has_if_team_scored_first_baron()
    {
        $this->hadFirstBaron()->shouldReturn($this->firstBaron);
    }

    public function it_has_if_team_scored_first_tower()
    {
        $this->hadFirstTower()->shouldReturn($this->firstTower);
    }

    public function it_has_if_team_scored_first_inhibitor()
    {
        $this->hadFirstInhibitor()->shouldReturn($this->firstInhibitor);
    }

    public function it_has_number_of_dragon_kills()
    {
        $this->getDragonKills()->shouldReturn($this->dragonKills);
    }

    public function it_has_number_of_rift_herald_kills()
    {
        $this->getRiftHeraldKills()->shouldReturn($this->riftHeraldKills);
    }

    public function it_has_number_of_baron_kills()
    {
        $this->getBaronKills()->shouldReturn($this->baronKills);
    }

    public function it_has_number_of_vilemaw_kills()
    {
        $this->getVilemawKills()->shouldReturn($this->vilemawKills);
    }

    public function it_has_number_of_tower_kills()
    {
        $this->getTowerKills()->shouldReturn($this->towerKills);
    }

    public function it_has_number_of_inhibitor_kills()
    {
        $this->getInhibitorKills()->shouldReturn($this->inhibitorKills);
    }

    public function it_has_dominion_victory_score()
    {
        $this->getDominionVictoryScore()->shouldReturn($this->dominionVictoryScore);
    }

    public function it_has_if_team_won()
    {
        $this->getWin()->shouldReturn($this->win);
    }
}

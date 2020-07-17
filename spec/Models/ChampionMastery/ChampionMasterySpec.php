<?php

namespace spec\App\Models\ChampionMastery;

use PhpSpec\ObjectBehavior;

class ChampionMasterySpec extends ObjectBehavior
{
    private $championId = 103;
    private $summonerId = "OHKSHr7Ubt8nqJ_0kgx5bRCB4YzQXYrQKJEZ6ACvsLlucec";
    private $championLevel = 5;
    private $chestGranted = false;
    private $championPoints = 66937;
    private $championPointsSinceLastLevel = 45337;
    private $championPointsUntilNextLevel = 0;
    private $tokensEarned = 1;
    private $lastPlayTime = 1572305756000;

    public function let()
    {
        $this->beConstructedWith(
            $this->championId,
            $this->summonerId,
            $this->championLevel,
            $this->chestGranted,
            $this->championPoints,
            $this->championPointsSinceLastLevel,
            $this->championPointsUntilNextLevel,
            $this->tokensEarned,
            $this->lastPlayTime
        );
    }

    public function it_has_a_champion_id()
    {
        $this->getChampionId()->shouldReturn($this->championId);
    }

    public function it_has_a_summoner_id()
    {
        $this->getSummonerId()->shouldReturn($this->summonerId);
    }

    public function it_has_a_champion_mastery_level()
    {
        $this->getLevel()->shouldReturn($this->championLevel);
    }

    public function it_has_if_chest_been_awarded()
    {
        $this->isChestAwarded()->shouldReturn($this->chestGranted);
    }

    public function it_has_champion_mastery_points()
    {
        $this->getMasteryPoints()->shouldReturn($this->championPoints);
    }

    public function it_has_mastery_points_since_last_level()
    {
        $this->getMasteryPointsThisLevel()->shouldReturn($this->championPointsSinceLastLevel);
    }

    public function it_has_mastery_points_until_next_level()
    {
        $this->getMasteryPointsNextLevel()->shouldReturn($this->championPointsUntilNextLevel);
    }

    public function it_has_number_of_tokens_earned()
    {
        $this->getTokensEarned()->shouldReturn($this->tokensEarned);
    }

    public function it_has_last_played_time()
    {
        $this->getLastTimePlayed()->shouldReturn($this->lastPlayTime);
    }
}
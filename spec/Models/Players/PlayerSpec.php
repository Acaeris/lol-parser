<?php

namespace spec\App\Models\Players;

use PhpSpec\ObjectBehavior;

class PlayerSpec extends ObjectBehavior
{
    private $accountId = 1;
    private $currentAccountId = 2;
    private $platformId = 3;
    private $currentPlatformId = 4;
    private $summonerId = 5;
    private $summonerName = 'Acaeris';
    private $profileIcon = 6;
    private $matchHistoryUri = 'test.url';

    public function let()
    {
        $this->beConstructedWith(
            $this->accountId,
            $this->currentAccountId,
            $this->platformId,
            $this->currentPlatformId,
            $this->summonerId,
            $this->summonerName,
            $this->profileIcon,
            $this->matchHistoryUri
        );
    }

    public function it_has_an_account_id()
    {
        $this->getAccountID()->shouldReturn($this->accountId);
    }

    public function it_has_a_current_account_id()
    {
        $this->getCurrentAccountID()->shouldReturn($this->currentAccountId);
    }

    public function it_has_a_platform_id()
    {
        $this->getPlatformID()->shouldReturn($this->platformId);
    }

    public function it_has_a_current_platform_id()
    {
        $this->getCurrentPlatformID()->shouldReturn($this->currentPlatformId);
    }

    public function it_has_a_summoner_id()
    {
        $this->getSummonerID()->shouldReturn($this->summonerId);
    }

    public function it_has_a_summoner_name()
    {
        $this->getSummonerName()->shouldReturn($this->summonerName);
    }

    public function it_has_a_profile_icon()
    {
        $this->getProfileIcon()->shouldReturn($this->profileIcon);
    }

    public function it_has_a_match_history_url()
    {
        $this->getMatchHistoryUri()->shouldReturn($this->matchHistoryUri);
    }
}

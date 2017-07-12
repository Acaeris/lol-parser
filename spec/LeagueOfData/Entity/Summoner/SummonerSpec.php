<?php

namespace spec\LeagueOfData\Entity\Summoner;

use PhpSpec\ObjectBehavior;

class SummonerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Summoner ID
            2, // Account ID
            "Acaeris", // Summoner Name
            30, // Summoner Level
            779, // Profile Icon ID
            "2017-07-01 17:53:38", // Revision Date
            "euw" // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Summoner\Summoner');
        $this->shouldImplement('LeagueOfData\Entity\Summoner\SummonerInterface');
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
            'summoner_id' => 1,
            'region' => "euw"
        ]);
    }

    public function it_has_all_data_available()
    {
        $this->getSummonerID()->shouldReturn(1);
        $this->getAccountID()->shouldReturn(2);
        $this->getName()->shouldReturn("Acaeris");
        $this->getLevel()->shouldReturn(30);
        $this->getProfileIconID()->shouldReturn(779);
        $this->getRevisionDate()->shouldReturnAnInstanceOf("\DateTime");
        $this->getRegion()->shouldReturn("euw");
    }
}

<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\RequestInterface;

class MatchListRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'Test Query', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\MatchListRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_a_request_type()
    {
        $this->type()->shouldReturn('match-list');
    }

    public function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    public function it_returns_the_correct_query_for_a_json_request()
    {
        $this->requestFormat(RequestInterface::REQUEST_JSON);
        $this->query()->shouldReturn('https://na.api.pvp.net/api/lol/na/v2.2/matchlist/by-summoner/');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(RequestInterface::REQUEST_SQL);
        $this->query()->shouldReturn('Test Query');
    }

    public function it_can_process_the_request_parameters()
    {
        $this->where()->shouldReturn(['region' => 'na']);
    }

    public function it_can_validate_a_correct_id_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner Match List request'))
            ->during('validate', [['id' => 1]]);
    }

    public function it_can_validate_an_incorrect_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner Match List request'))
            ->during('validate', [['id' => 'test']]);
    }

    public function it_can_validate_a_correct_region_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner Match List request'))
            ->during('validate', [['region' => 'na']]);
    }

    public function it_can_validate_an_incorrect_region_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner Match List request'))
            ->during('validate', [['region' => 'an']]);
    }
}

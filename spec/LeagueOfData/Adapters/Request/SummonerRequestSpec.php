<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\Request;

class SummonerRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na', 'id' => 1 ], 'Test Query', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\SummonerRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    public function it_has_a_type()
    {
        $this->type()->shouldReturn('summoners');
    }

    public function it_returns_the_correct_query_for_a_json_request_by_id()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->query()->shouldReturn('summoner/v3/summoners/1');
    }

    public function it_returns_the_correct_query_for_a_json_request_by_name()
    {
        $this->beConstructedWith(['region' => 'na', 'name' => 'Acaeris']);
        $this->requestFormat(Request::TYPE_JSON);
        $this->query()->shouldReturn('summoner/v3/summoners/by-name/Acaeris');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->query()->shouldReturn('Test Query');
    }

    public function it_can_process_the_request_parameters()
    {
        $this->where()->shouldReturn(['region' => 'na', 'id' => 1]);
    }

    public function it_can_validate_a_correct_id_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner request'))
            ->during('validate', [['id' => 1]]);
    }

    public function it_can_validate_an_incorrect_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner request'))
            ->during('validate', [['id' => 'test']]);
    }

    public function it_can_validate_a_correct_region_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner request'))
            ->during('validate', [['region' => 'na', 'id' => 1]]);
    }

    public function it_can_validate_an_incorrect_region_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner request'))
            ->during('validate', [['region' => 'an', 'id' => 1]]);
    }
}

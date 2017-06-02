<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\Request;

class ChampionRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'title', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\ChampionRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_all_parameters()
    {
        $this->data()->shouldReturn(['Test Data']);
        $this->type()->shouldReturn('champions');
    }

    public function it_returns_the_api_endpoint_for_a_json_request()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->query()->shouldReturn('static-data/v3/champions');
    }

    public function it_returns_the_api_endpoint_for_specific_champion()
    {
        $this->beConstructedWith(['region' => 'na', 'champion_id' => 1], 'title', ['Test Data']);
        $this->requestFormat(Request::TYPE_JSON);
        $this->query()->shouldReturn('static-data/v3/champions/1');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->query()->shouldReturn('SELECT title FROM champions WHERE region = :region');
    }

    public function it_can_process_the_request_parameters_for_sql()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->where()->shouldReturn(['region' => 'na']);
    }

    public function it_adds_api_defaults_to_request_parameters_for_api()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->where()->shouldReturn(['region' => 'na', 'champData' => 'all']);
    }

    public function it_ignores_champion_id_for_where()
    {
        $this->beConstructedWith(['region' => 'na', 'champion_id' => 1], 'title', ['Test Data']);
        $this->requestFormat(Request::TYPE_JSON);
        $this->where()->shouldReturn(['region' => 'na', 'champData' => 'all']);
    }

    public function it_can_validate_parameters()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['champion_id' => 1]]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['champion_id' => 'test']]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['champion_id' => 1.2]]);
    }
}

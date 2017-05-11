<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\Request;

class ChampionRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'test_column', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\ChampionRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    public function it_has_a_type()
    {
        $this->type()->shouldReturn('champions');
    }

    public function it_returns_the_api_endpoint_for_a_json_request()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->query()->shouldReturn('static-data/v3/champions');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->query()->shouldReturn('SELECT test_column FROM champions WHERE region = :region');
    }

    public function it_can_process_the_request_parameters()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->where()->shouldReturn(['region' => 'na']);
    }

    public function it_adds_api_defaults_to_where()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->where()->shouldReturn(['region' => 'na', 'champListData' => 'all']);
    }

    public function it_can_validate_a_correct_id_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['id' => 1, 'champion_id' => 1]]);
    }

    public function it_can_validate_an_incorrect_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['id' => 'test']]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['id' => 1.2]]);
    }

    public function it_can_validate_an_incorrect_champion_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['champion_id' => 'test']]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion request'))
            ->during('validate', [['champion_id' => 1.2]]);
    }
}

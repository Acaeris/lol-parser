<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\RequestInterface;

class SummonerRequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['region' => 'na', 'id' => 1 ], 'Test Query', ['Test Data']);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\SummonerRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    function it_has_a_request_type()
    {
        $this->type()->shouldReturn('item');
    }

    function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    function it_returns_the_correct_query_for_a_json_request_bt_id()
    {
        $this->requestFormat(RequestInterface::REQUEST_JSON);
        $this->query()->shouldReturn('https://na.api.pvp.net/api/lol/na/v1.4/summoner/1');
    }

    function it_returns_the_correct_query_for_a_json_request_by_name()
    {
        $this->beConstructedWith(['region' => 'na', 'name' => 'Acaeris']);
        $this->requestFormat(RequestInterface::REQUEST_JSON);
        $this->query()->shouldReturn('https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/Acaeris');
    }

    function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(RequestInterface::REQUEST_SQL);
        $this->query()->shouldReturn('Test Query');
    }

    function it_can_process_the_request_parameters()
    {
        $this->where()->shouldReturn(['region' => 'na', 'id' => 1]);
    }

    function it_can_validate_a_correct_id_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner request'))
            ->during('validate', [['id' => 1]]);
    }

    function it_can_validate_an_incorrect_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Summoner request'))
            ->during('validate', [['id' => 'test']]);
    }

    function it_can_validate_a_correct_region_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner request'))
            ->during('validate', [['region' => 'na', 'id' => 1]]);
    }

    function it_can_validate_an_incorrect_region_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid Region supplied for Summoner request'))
            ->during('validate', [['region' => 'an', 'id' => 1]]);
    }
}

<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\Request;

class ItemStatsRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'test_column', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\ItemStatsRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    public function it_has_a_type()
    {
        $this->type()->shouldReturn('item_stats');
    }

    public function it_cannot_be_used_for_a_json_request()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->shouldThrow(new \Exception('Cannot create API query for stats alone'))
            ->during('query');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->query()->shouldReturn('SELECT test_column FROM item_stats WHERE region = :region');
    }

    public function it_can_process_the_request_parameters()
    {
        $this->where()->shouldReturn(['region' => 'na']);
    }

    public function it_can_validate_a_correct_id_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Item Stats request'))
            ->during('validate', [['item_id' => 1]]);
    }

    public function it_can_validate_an_incorrect_id_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Item Stats request'))
            ->during('validate', [['item_id' => 'test']]);
    }
}

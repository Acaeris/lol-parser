<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\Request;

class ChampionSpellRequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'test_column', ['Test Data']);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\ChampionSpellRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    public function it_has_all_parameters()
    {
        $this->data()->shouldReturn(['Test Data']);
        $this->type()->shouldReturn('champion_spells');
        $this->where()->shouldReturn(['region' => 'na']);
    }

    public function it_cannot_be_used_for_a_json_request()
    {
        $this->requestFormat(Request::TYPE_JSON);
        $this->shouldThrow(new \Exception('Cannot create API query for spells alone'))
            ->during('query');
    }

    public function it_returns_the_correct_query_for_an_sql_request()
    {
        $this->requestFormat(Request::TYPE_SQL);
        $this->query()->shouldReturn("SELECT test_column FROM champion_spells WHERE region = :region"
            . " ORDER BY CASE WHEN spell_key = 'Q' THEN 0 WHEN spell_key = 'W' THEN 1 WHEN spell_key = 'E' THEN 2"
            . " ELSE 3 END");
    }

    public function it_can_validate_parameters()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid ID supplied for Champion Stats request'))
            ->during('validate', [['champion_id' => 1]]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion Stats request'))
            ->during('validate', [['champion_id' => 'test']]);
        $this->shouldThrow(new \InvalidArgumentException('Invalid ID supplied for Champion Stats request'))
            ->during('validate', [['champion_id' => 1.2]]);
    }
}

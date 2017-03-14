<?php

namespace spec\LeagueOfData\Adapters\Request;

use PhpSpec\ObjectBehavior;

class VersionRequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['region' => 'na'], 'Test Query', ['Test Data']);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Adapters\Request\VersionRequest');
        $this->shouldImplement('LeagueOfData\Adapters\RequestInterface');
    }

    function it_has_a_request_type()
    {
        $this->type()->shouldReturn('version');
    }

    function it_has_request_data()
    {
        $this->data()->shouldReturn(['Test Data']);
    }

    function it_can_validate_a_correct_region_parameter()
    {
        $this->shouldNotThrow(new \InvalidArgumentException('Invalid Region supplied for Version request'))
            ->during('validate', [['region' => 'na']]);
    }

    function it_can_validate_an_incorrect_region_parameter()
    {
        $this->shouldThrow(new \InvalidArgumentException('Invalid Region supplied for Version request'))
            ->during('validate', [['region' => 'an']]);
    }
}

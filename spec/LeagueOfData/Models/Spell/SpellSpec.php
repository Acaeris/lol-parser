<?php

namespace spec\LeagueOfData\Models\Spell;

use PhpSpec\ObjectBehavior;

class SpellSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1,
            'Test Spell',
            'Q',
            'test_spell',
            'The spell does a thing.',
            "Test Tooltip",
            5,
            [10, 9, 8, 7, 6],
            [100, 90, 80, 70, 60]
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Spell\Spell');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\SpellInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_an_id()
    {
        $this->getID()->shouldReturn(1);
    }

    public function it_has_a_name()
    {
        $this->name()->shouldReturn('Test Spell');
    }

    public function it_has_a_key_binding()
    {
        $this->keyBinding()->shouldReturn('Q');
    }

    public function it_has_a_file_name()
    {
        $this->fileName()->shouldReturn('test_spell');
    }

    public function it_has_a_desccription()
    {
        $this->description()->shouldReturn('The spell does a thing.');
    }

    public function it_has_a_tooltip()
    {
        $this->tooltip()->shouldReturn("Test Tooltip");
    }

    public function it_has_cooldowns()
    {
        $this->cooldowns()->shouldReturn([10, 9, 8, 7, 6]);
    }

    public function it_can_return_cooldown_for_a_given_rank()
    {
        $this->cooldownByRank(3)->shouldReturn(8);
    }

    public function it_has_costs()
    {
        $this->costs()->shouldReturn([100, 90, 80, 70, 60]);
    }

    public function it_can_return_cost_for_a_given_rank()
    {
        $this->costByRank(3)->shouldReturn(80);
    }

    public function it_has_a_max_rank()
    {
        $this->maxRank()->shouldReturn(5);
    }

    public function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => 'Test Spell',
            'key' => 'Q',
            'file' => 'test_spell',
            'description' => 'The spell does a thing.',
            'maxrank' => 5,
            'cooldown' => [10, 9, 8, 7, 6],
            'costs' => [100, 90, 80, 70, 60]
        ]);
    }
}

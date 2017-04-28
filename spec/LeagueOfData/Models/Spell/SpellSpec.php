<?php

namespace spec\LeagueOfData\Models\Spell;

use PhpSpec\ObjectBehavior;

class SpellSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Spell ID
            'Test Spell', // Spell Name
            'Q', // Spell default key binding
            'test_spell', // File Name
            'The spell does a thing.', // Description
            "Test Tooltip", // Tooltip
            "Mana", // Resource Type
            5, // Max Rank
            [10, 9, 8, 7, 6], // Cooldowns
            [100, 200, 300, 400, 500], // Ranges
            [100, 90, 80, 70, 60] // Costs
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Spell\Spell');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getID()->shouldReturn(1);
        $this->name()->shouldReturn('Test Spell');
        $this->keyBinding()->shouldReturn('Q');
        $this->fileName()->shouldReturn('test_spell');
        $this->description()->shouldReturn('The spell does a thing.');
        $this->tooltip()->shouldReturn("Test Tooltip");
        $this->resourceType()->shouldReturn("Mana");
        $this->maxRank()->shouldReturn(5);
        $this->cooldowns()->shouldReturn([10, 9, 8, 7, 6]);
        $this->ranges()->shouldReturn([100, 200, 300, 400, 500]);
        $this->costs()->shouldReturn([100, 90, 80, 70, 60]);
    }

    public function it_can_calculate_cooldown_for_a_given_rank()
    {
        $this->cooldownByRank(3)->shouldReturn(8);
    }

    public function it_checks_max_rank_when_asked_for_cooldown()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('cooldownByRank', [6]);
    }

    public function it_can_return_a_range_for_a_given_rank()
    {
        $this->rangeByRank(3)->shouldReturn(300);
    }

    public function it_checks_max_rank_when_asked_for_range()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('rangeByRank', [6]);
    }

    public function it_can_return_cost_for_a_given_rank()
    {
        $this->costByRank(3)->shouldReturn(80);
    }

    public function it_checks_max_rank_when_asked_for_costs()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('costByRank', [6]);
    }
}

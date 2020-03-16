<?php

namespace spec\App\Models\Spells;

use PhpSpec\ObjectBehavior;

class SpellVarSpec extends ObjectBehavior
{
    private $key = 'A';
    private $ranksWith = 'Ranks';
    private $dyn = 'Dyn';
    private $link = 'Link';
    private $coeff = [1,2,3];

    public function let()
    {
        $this->beConstructedWith($this->key, $this->ranksWith, $this->dyn, $this->link, $this->coeff);
    }

    public function it_has_a_spell_key()
    {
        $this->getKey()->shouldReturn($this->key);
    }

    public function it_has_ranks_with_data()
    {
        $this->getRanksWith()->shouldReturn($this->ranksWith);
    }

    public function it_has_dyn_data()
    {
        $this->getDyn()->shouldReturn($this->dyn);
    }

    public function it_has_link_data()
    {
        $this->getLink()->shouldReturn($this->link);
    }

    public function it_has_coeff_data()
    {
        $this->getCoeff()->shouldReturn($this->coeff);
    }
}

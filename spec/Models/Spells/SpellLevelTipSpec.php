<?php

namespace spec\App\Models\Spells;

use PhpSpec\ObjectBehavior;

class SpellLevelTipSpec extends ObjectBehavior
{
    private $labels = ['Label'];
    private $effects = ['Effect'];

    public function let()
    {
        $this->beConstructedWith($this->labels, $this->effects);
    }

    public function it_has_labels()
    {
        $this->getLabels()->shouldReturn($this->labels);
    }

    public function it_has_effects()
    {
        $this->getEffects()->shouldReturn($this->effects);
    }
}

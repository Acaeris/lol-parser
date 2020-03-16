<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;
use App\Models\Image;

class ChampionPassiveSpec extends ObjectBehavior
{
    public function it_has_all_core_parts(
        Image $image
    ) {
        $abilityName = 'Combustion';
        $description = 'Test description';
        $sanitisedDescription = 'Sanitised description';

        $this->beConstructedWith(
            $abilityName,
            $image,
            $description,
            $sanitisedDescription
        );

        $this->getName()->shouldReturn($abilityName);
        $this->getImage()->shouldReturn($image);
        $this->getDescription()->shouldReturn($description);
        $this->getSanitisedDescription()->shouldReturn($sanitisedDescription);
    }
}

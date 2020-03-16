<?php

namespace spec\App\Models;

use PhpSpec\ObjectBehavior;

class ImageSpec extends ObjectBehavior
{
    public function it_has_all_core_parts()
    {
        $full = 'Test Full';
        $group = 'Test Group';
        $sprite = 'Test Sprite';
        $height = 1;
        $width = 2;
        $xPos = 3;
        $yPos = 4;

        $this->beConstructedWith(
            $full,
            $group,
            $sprite,
            $height,
            $width,
            $xPos,
            $yPos
        );

        $this->getFullPath()->shouldReturn($full);
        $this->getGroup()->shouldReturn($group);
        $this->getSpritePath()->shouldReturn($sprite);
        $this->getHeight()->shouldReturn($height);
        $this->getWidth()->shouldReturn($width);
        $this->getXPos()->shouldReturn($xPos);
        $this->getYPos()->shouldReturn($yPos);
    }
}

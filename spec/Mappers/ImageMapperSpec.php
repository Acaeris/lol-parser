<?php

namespace spec\App\Mappers;

use App\Models\Image;
use PhpSpec\ObjectBehavior;

class ImageMapperSpec extends ObjectBehavior
{
    private $mockData = [
        'full' => 'full.png',
        'group' => 'champion',
        'sprite' => 'sprite.png',
        'x' => 0,
        'y' => 0,
        'w' => 64,
        'h' => 64
    ];

    public function it_can_map_array_data()
    {
        $this->mapFromArray($this->mockData)->shouldReturnAnInstanceOf(Image::class);
    }
}

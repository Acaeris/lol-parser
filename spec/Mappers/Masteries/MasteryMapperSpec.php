<?php

namespace spec\App\Mappers\Masteries;

use App\Mappers\ImageMapper;
use App\Models\Image;
use App\Models\Masteries\Mastery;
use PhpSpec\ObjectBehavior;

class MasteryMapperSpec extends ObjectBehavior
{
    private $mockData = [
        'mastery_id' => 1,
        'mastery_name' => 'Test Mastery',
        'description' => [
            'Test Description'
        ],
        'image' => [
            'full' => 'full.png',
            'group' => 'champion',
            'sprite' => 'sprite.png',
            'x' => 0,
            'y' => 0,
            'w' => 64,
            'h' => 64
        ],
        'prereq' => 'Test Prereq',
        'ranks' => 0,
        'mastery_tree' => 'Test tree',
        'region' => 'euw',
        'version' => '7.4.3'
    ];

    public function let(ImageMapper $imageMapper, Image $image) {
        $imageMapper->mapFromArray($this->mockData['image'])->willReturn($image);
        $this->beConstructedWith($imageMapper);
    }

    public function it_can_map_data_from_an_array()
    {
        $this->mapFromArray($this->mockData)->shouldReturnAnInstanceOf(Mastery::class);
    }
}
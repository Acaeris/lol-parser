<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;

use App\Mappers\ImageMapper;
use App\Models\Image;

class ChampionPassiveMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "image" => [
            "full" => "Annie_Passive.png",
            "group" => "passive",
            "sprite" => "passive0.png",
            "h" => 48,
            "w" => 48,
            "y" => 0,
            "x" => 288
        ],
        "sanitizedDescription" => "Test Sanitised Description",
        "name" => "Pyromania",
        "description" => "Test Description"
    ];

    public function let(
        ImageMapper $imageMapper
    ) {
        $this->beConstructedWith($imageMapper);
    }

    public function it_can_map_array_data(
        ImageMapper $imageMapper,
        Image $image
    ) {
        $imageMapper->mapFromArray($this->mockData['image'])->willReturn($image);

        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionPassive');
    }
}

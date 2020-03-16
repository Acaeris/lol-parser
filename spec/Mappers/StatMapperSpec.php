<?php

namespace spec\App\Mappers;

use App\Models\Stat;
use PhpSpec\ObjectBehavior;

class StatMapperSpec extends ObjectBehavior
{
    private $mockData = [
        'name' => 'Strength',
        'stat' => '+2'
    ];

    public function it_can_map_array_data()
    {
        $this->mapFromArray($this->mockData)->shouldReturnAnInstanceOf(Stat::class);
    }
}
<?php

namespace spec\App\Models;

use PhpSpec\ObjectBehavior;

class SummonerSpec extends ObjectBehavior
{
    private $summonerId = 1;
    private $name = 'Acaeris';
    private $level = 2;
    private $iconId = 3;
    private $revisionDate = '01/01/2020';
    private $arrayData = [
        'id' => 1,
        'name' => 'Acaeris',
        'level' => 2,
        'icon' => 3,
        'revisionDate' => '01/01/2020'
    ];

    public function let()
    {
        $this->beConstructedWith(
            $this->summonerId,
            $this->name,
            $this->level,
            $this->iconId,
            $this->revisionDate
        );
    }

    public function it_has_a_summoner_id()
    {
        $this->getID()->shouldReturn($this->summonerId);
    }

    public function it_has_a_summoner_name()
    {
        $this->getName()->shouldReturn($this->name);
    }

    public function it_has_summoner_level()
    {
        $this->getLevel()->shouldReturn($this->level);
    }

    public function it_has_summoner_icon_id()
    {
        $this->getIconID()->shouldReturn($this->iconId);
    }

    public function it_has_revision_date()
    {
        $this->getRevisionDate()->shouldReturn($this->revisionDate);
    }

    public function it_has_object_to_array_conversion()
    {
        $this->toArray()->shouldReturn($this->arrayData);
    }
}
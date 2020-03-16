<?php

namespace spec\App\Models\Masteries;

use PhpSpec\ObjectBehavior;
use App\Models\Image;

class MasterySpec extends ObjectBehavior
{
    private $masteryId = 1;
    private $masteryName = 'Strength of Titans';
    private $description = ['Test description'];
    private $prereq = 'Prerequisite';
    private $ranks = 2;
    private $masteryTree = 'Mastery tree';
    private $region = 'EUW';
    private $version = '10.1.2';

    public function let(Image $image)
    {
        $this->beConstructedWith(
            $this->masteryId,
            $this->masteryName,
            $this->description,
            $image,
            $this->prereq,
            $this->ranks,
            $this->masteryTree,
            $this->region,
            $this->version
        );
    }

    public function it_has_a_mastery_id()
    {
        $this->getMasteryId()->shouldReturn($this->masteryId);
    }

    public function it_has_a_mastery_name()
    {
        $this->getMasteryName()->shouldReturn($this->masteryName);
    }

    public function it_has_a_description()
    {
        $this->getDescription()->shouldReturn($this->description);
    }

    public function it_has_an_image(Image $image)
    {
        $this->getIcon()->shouldReturn($image);
    }

    public function it_has_prerequisites()
    {
        $this->getPrerequisite()->shouldReturn($this->prereq);
    }

    public function it_has_number_of_available_ranks()
    {
        $this->getRanks()->shouldReturn($this->ranks);
    }

    public function it_has_an_associated_mastery_tree()
    {
        $this->getMasteryTree()->shouldReturn($this->masteryTree);
    }

    public function it_has_region_data()
    {
        $this->getRegion()->shouldReturn($this->region);
    }

    public function it_has_version_data()
    {
        $this->getVersion()->shouldReturn($this->version);
    }
}

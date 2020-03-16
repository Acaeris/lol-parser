<?php

namespace spec\App\Models\Rune;

use App\Models\StatInterface;
use PhpSpec\ObjectBehavior;

class RuneSpec extends ObjectBehavior
{
    private $runeId = 1;
    private $runeName = 'Rune';
    private $description = 'Description';
    private $imageName = 'Rune.png';
    private $stats = [];
    private $tags = ['Tag'];
    private $version = '10.1.2';
    private $region = 'EUW';

    public function let(StatInterface $stat)
    {
        $stat->getStatName()->willReturn('Stat');
        $stat->getStatModifier()->willReturn(1.0);
        $this->stats[] = $stat;

        $this->beConstructedWith(
            $this->runeId,
            $this->runeName,
            $this->description,
            $this->imageName,
            $this->stats,
            $this->tags,
            $this->version,
            $this->region
        );
    }

    public function it_has_a_data_key()
    {
        $this->getKeyData()->shouldReturn([
            'rune_id' => $this->runeId,
            'version' => $this->version,
            'region' => $this->region
        ]);
    }

    public function it_has_an_ID()
    {
        $this->getRuneID()->shouldReturn($this->runeId);
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn($this->runeName);
    }

    public function it_has_a_description()
    {
        $this->getDescription()->shouldReturn($this->description);
    }

    public function it_has_an_image()
    {
        $this->getImageName()->shouldReturn($this->imageName);
    }

    public function it_has_a_collection_of_stats()
    {
        $this->getStats()->shouldReturn($this->stats);
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('Stat')->shouldReturn(1.0);
    }

    public function it_has_a_collection_of_tags()
    {
        $this->getTags()->shouldReturn($this->tags);
    }

    public function it_has_version_data()
    {
        $this->getVersion()->shouldReturn($this->version);
    }

    public function it_has_region_data()
    {
        $this->getRegion()->shouldReturn($this->region);
    }
}
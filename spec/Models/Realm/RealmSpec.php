<?php

namespace spec\App\Models\Realm;

use PhpSpec\ObjectBehavior;

class RealmSpec extends ObjectBehavior
{
    private $sourceUrl = 'CDN';
    private $version = '10.1.2';

    public function let()
    {
        $this->beConstructedWith($this->sourceUrl, $this->version);
    }

    public function it_has_a_data_key()
    {
        $this->getKeyData()->shouldReturn([
            'version' => $this->version
        ]);
    }

    public function it_has_a_source_url()
    {
        $this->getSourceUrl()->shouldReturn($this->sourceUrl);
    }

    public function it_has_version_data()
    {
        $this->getVersion()->shouldReturn($this->version);
    }
}
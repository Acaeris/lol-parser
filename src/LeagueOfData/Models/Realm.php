<?php

namespace LeagueOfData\Models;

use LeagueOfData\Models\Interfaces\RealmInterface;
use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

final class Realm implements RealmInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var string CDN url
     */
    private $cdn;

    /**
     * @var string DD version
     */
    private $version;

    /**
     * Construct a Realm object
     *
     * @param string $cdn     CDN url
     * @param string $version DataDragon version
     */
    public function __construct(string $cdn, string $version)
    {
        $this->constructImmutable();

        $this->cdn = $cdn;
        $this->version = $version;
    }

    /**
     * CDN content url for this realm
     *
     * @return string CDN url
     */
    public function sourceUrl() : string
    {
        return $this->cdn;
    }

    /**
     * Data Dragon version number
     *
     * @return string Version
     */
    public function version() : string
    {
        return $this->version;
    }

    /**
     * Correctly convert the object to an array.
     * Use instead of PHP's type conversion
     *
     * @return array Realm data as an array
     */
    public function toArray() : array
    {
        return [
            'version' => $this->version,
            'cdn' => $this->cdn
        ];
    }
}

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
     * @var string Region
     */
    private $region;

    /**
     * Construct a Realm object
     *
     * @param string $cdn CDN url
     * @param string $version DataDragon version
     * @param string $region Realm region
     */
    public function __construct(string $cdn, string $version, string $region)
    {
        $this->constructImmutable();

        $this->cdn = $cdn;
        $this->version = $version;
        $this->region = $region;
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
     * Realm Region
     *
     * @return string Region
     */
    public function region() : string
    {
        return $this->region;
    }
}

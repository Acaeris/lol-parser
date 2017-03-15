<?php

namespace LeagueOfData\Models;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\VersionInterface;

final class Version implements VersionInterface, ImmutableInterface
{
    use ImmutableTrait {
        __construct as constructImmutable;
    }

    /**
     * @var string Full version string
     */
    private $fullVersion;

    /**
     * @var int Season number
     */
    private $season;

    /**
     * @var int Major version number 
     */
    private $version;

    /**
     * @var int Hotfix ID
     */
    private $hotfix;

    /**
     * Construct a version object
     * 
     * @param string $data Version
     */
    public function __construct(string $data)
    {
        $this->constructImmutable();

        $parts = explode('.', $data);
        $this->fullVersion = $data;
        $this->season = (int) $parts[0];
        $this->version = (int) $parts[1];
        $this->hotfix = (int) $parts[2];
    }

    /**
     * Full version string
     * 
     * @return string
     */
    public function fullVersion() : string
    {
        return $this->fullVersion;
    }

    /**
     * Season number
     * 
     * @return int
     */
    public function season() : int
    {
        return $this->season;
    }

    /**
     * Major version number
     * 
     * @return int
     */
    public function majorVersion() : int
    {
        return $this->version;
    }

    /**
     * Hotfix ID
     * 
     * @return int
     */
    public function hotfix() : int
    {
        return $this->hotfix;
    }
}

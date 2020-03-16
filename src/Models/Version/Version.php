<?php

namespace App\Models\Version;

use App\Models\Immutable\ImmutableInterface;
use App\Models\Immutable\ImmutableTrait;
use App\Models\EntityInterface;

final class Version implements ImmutableInterface, EntityInterface, VersionInterface
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
     * Get key identifying data for this object
     *
     * @return array
     */
    public function getKeyData() : array
    {
        return [
            'full_version' => $this->fullVersion
        ];
    }

    /**
     * Full version string
     *
     * @return string
     */
    public function getFullVersion() : string
    {
        return $this->fullVersion;
    }

    /**
     * Season number
     *
     * @return int
     */
    public function getSeason() : int
    {
        return $this->season;
    }

    /**
     * Major version number
     *
     * @return int
     */
    public function getMajorVersion() : int
    {
        return $this->version;
    }

    /**
     * Hotfix ID
     *
     * @return int
     */
    public function getHotfix() : int
    {
        return $this->hotfix;
    }
}

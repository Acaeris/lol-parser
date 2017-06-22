<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Version\Version;

/**
 * Version object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonVersions implements FetchServiceInterface
{
    /**
     * @var AdapterInterface DB adapter
     */
    private $dbAdapter;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Version objects
     */
    private $versions = [];

    /**
     * Set up version service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->dbAdapter = $adapter;
        $this->log = $log;
    }

    /**
     * Factory for creating version objects
     *
     * @param string $version
     * @return EntityInterface
     */
    public function create(array $version) : EntityInterface
    {
        return new Version($version[0]);
    }

    /**
     * Find all Version data
     *
     * @param RequestInterface $request
     * @return array Version objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetch versions from API");
        $response = $this->dbAdapter->fetch($request);
        $this->versions = [];
        $this->processResults($response);
        $this->log->debug(count($this->versions)." versions fetch from API");

        return $this->versions;
    }

    /**
     * Collection of Version objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->versions;
    }

    /**
     * Convert result data into version objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $version) {
                $this->versions[] = $this->create([$version]);
            }
        }
    }
}

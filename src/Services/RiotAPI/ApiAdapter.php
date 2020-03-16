<?php

namespace App\Services\RiotAPI;

use bandwidthThrottle\tokenBucket\storage\StorageException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use App\Services\TokenBucket\BucketFactory;

class ApiAdapter implements ApiAdapterInterface
{
    /**
     * Base API URL
     */
    const API_URL = 'https://{region}.api.riotgames.com/lol/';

    /**
     * Current API url
     *
     * @var string
     */
    private $apiUrl;

    /**
     * Logging Service
     *
     * @var LoggerInterface
     */
    private $log;

    /**
     * Guzzle Client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * API connection key
     *
     * @var string
     */
    private $apiKey;

    /**
     * Token Bucket Consumer, used for rate limiting
     *
     * @var BlockingConsumer
     */
    private $consumer;

    public function __construct(
        LoggerInterface $log,
        ClientInterface $client,
        string $apiKey
    ) {
        $this->log = $log;
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->consumer = BucketFactory::createBucket('api', 10);
        $this->setRegion('EUW');
    }

    /**
     * Fetch data from Riot API
     *
     * @param string $url - API url
     * @param string[] $params - Query params
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws StorageException
     */
    public function fetch(string $url, array $params): ResponseInterface
    {
        try {
            $this->consumer->consume(1);
            $response = $this->client->request(
                'GET',
                $this->apiUrl . $url,
                [
                    'query' => array_merge($params, ['api_key' => $this->apiKey]),
                    'headers' => [ 'Content-type' => 'application/json' ]
                ]
            );
        } catch (ClientException $exception) {
            $this->log->error('Guzzle Client Error');
            $response = $exception->getResponse();
        } catch (ServerException $exception) {
            $this->log->error('Guzzle Server Error');
            $response = $exception->getResponse();
        }
        return $response;
    }

    /**
     * Set the region to request data from
     *
     * @param string $region
     */
    public function setRegion(string $region)
    {
        $region .= in_array($region, ['ru', 'kr']) ? '' : '1';
        $this->apiUrl = str_replace('{region}', $region, self::API_URL);
    }
}

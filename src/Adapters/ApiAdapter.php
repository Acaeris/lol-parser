<?php

namespace App\Adapters;

use App\Services\TokenBucket\BucketFactory;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\StorageException;
use bandwidthThrottle\tokenBucket\TimeoutException;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * API Adapter class
 * @package App\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ApiAdapter implements AdapterInterface
{

    const API_URL = "https://{region}.api.riotgames.com/lol/{endpoint}";
    const API_PROCEED = 1;
    const API_SKIP = 2;
    const API_REPEAT = 3;
    const API_FAIL = 0;
    /**
     * @var BlockingConsumer
     */
    private $consumer;
    private $log;
    private $client;
    private $apiKey;
    private $attempts = 0;

    private $apiEndpoint;
    private $params = [];

    /**
     * Set up the API adapter
     *
     * @param LoggerInterface $log
     * @param ClientInterface $client
     * @param string          $apiKey
     */
    public function __construct(LoggerInterface $log, ClientInterface $client, string $apiKey)
    {
        $this->log = $log;
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->consumer = BucketFactory::createBucket('api', 10);
    }

    /**
     * Set the API options
     *
     * @param string $apiEndpoint Endpoint to access
     * @param array $params Parameters to use
     * @return AdapterInterface Fluid interface
     */
    public function setOptions(string $apiEndpoint, array $params) : AdapterInterface
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->params = $params;
        return $this;
    }

    /**
     * Fetch data from API
     *
     * @return array Fetch response
     * @throws GuzzleException Exception with actual API request.
     * @throws TimeoutException Timeout exception when trying to update bucket.
     * @throws StorageException Unable to store bucket data
     */
    public function fetch() : array
    {
        $this->attempts++;
        try {
            $this->consumer->consume(1);
            $response = $this->client->request('GET', $this->buildQuery(), [
                'query' => array_merge($this->params, ['api_key' => $this->apiKey]),
                'headers' => [ 'Content-type' => 'application/json' ],
            ]);
        } catch (ServerException $e) {
            $this->handleApiException('Server', $e);
            $response = $e->getResponse();
        } catch (ClientException $e) {
            $this->handleApiException('Client', $e);
            $response = $e->getResponse();
        }
        return $this->checkResponse($response);
    }

    /**
     * Builds the API query
     *
     * @return string
     */
    public function buildQuery() : string
    {
        $region = $this->params['region'] . (in_array($this->params['region'], ['ru', 'kr']) ? '' : '1');
        $url = str_replace('{region}', $region, self::API_URL).(
            isset($this->params['id']) ? '/'.$this->params['id'] : ''
        );
        return str_replace('{endpoint}', $this->apiEndpoint, $url);
    }

    /**
     * Handle API exceptions
     *
     * @param string $type
     * @param Exception $exception
     */
    private function handleApiException(string $type, Exception $exception)
    {
        $this->log->error('Guzzle '.$type.' Exception:', [
            'status' => $exception->getResponse()->getStatusCode(),
            'request' => $exception->getRequest()->getUri(),
            'response' => $exception->getResponse()->getBody(),
        ]);
    }

    /**
     * Checks the response code from the API
     *
     * @param ResponseInterface $response
     * @return array Data bag version of the JSON response
     * @throws GuzzleException Exception with actual API request.
     * @throws TimeoutException Timeout exception when trying to update bucket.
     * @throws StorageException Unable to store bucket data
     */
    public function checkResponse(ResponseInterface $response) : array
    {
        switch ($response->getStatusCode()) {
            case 200:
                $this->attempts = 0;
                return json_decode($response->getBody(), true);
            case 503:
                if ($this->attempts < 3) {
                    $this->log->info("API unavailable. Waiting to retry.");
                    sleep(1);
                    return $this->fetch();
                }
                $this->log->info("API unavailable after 3 attempts. Skipping.");
                break;
            case 404:
                $this->log->info("Data unavailable. Skipping.");
                break;
            default:
                $this->log->error("Unknown response: ".$response->getStatusCode());
                break;
        }

        $this->attempts = 0;
        return [];
    }
}

<?php

namespace LeagueOfData\Adapters;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;

/**
 * API Adapter class
 * @package LeagueOfData\Adapters
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

    private $log;
    private $client;
    private $apiKey;
    private $attempts = 0;

    /**
     * Set up the API adapter
     *
     * @param LoggerInterface $log
     * @param Client          $client
     * @param string          $apiKey
     */
    public function __construct(LoggerInterface $log, Client $client, string $apiKey)
    {
        $this->log = $log;
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Fetch data from API
     *
     * @param string $apiEndpoint API Endpoint
     * @param array $params API parameters
     *
     * @return array Fetch response
     */
    public function fetch(string $apiEndpoint, array $params) : array
    {
        $this->attempts++;
        try {
            $response = $this->client->get($this->buildQuery($apiEndpoint, $params), [
                'query' => array_merge($params, ['api_key' => $this->apiKey]),
                'headers' => [ 'Content-type' => 'application/json' ],
            ]);
        } catch (ServerException $e) {
            $this->handleApiException('Server', $e);
            $response = $e->getResponse();
        } catch (ClientException $e) {
            $this->handleApiException('Client', $e);
            $response = $e->getResponse();
        }
        $state = $this->checkResponse($response);
        switch ($state) {
            case self::API_PROCEED:
                $this->attempts = 0;
                return json_decode($response->getBody(), true);
            case self::API_REPEAT:
                sleep(1);

                return $this->fetch($apiEndpoint, $params);
            case self::API_SKIP:
                $this->attempts = 0;
                return [];
            case self::API_FAIL:
                exit;
        }
    }

    /**
     * Handle API exceptions
     *
     * @param string $type
     * @param \Exception $exception
     */
    private function handleApiException(string $type, \Exception $exception)
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
     * @return int
     */
    public function checkResponse(ResponseInterface $response) : int
    {
        switch ($response->getStatusCode()) {
            case 200:
                return self::API_PROCEED;
            case 503:
                if ($this->attempts < 3) {
                    $this->log->info("API unavailable. Waiting to retry.");

                    return self::API_REPEAT;
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

        return self::API_SKIP;
    }

    /**
     * Builds the API query
     *
     * @param string $apiEndpoint API Endpoint
     * @param array  $params      API Parameters
     * @return string
     */
    public function buildQuery(string $apiEndpoint, array $params) : string
    {
        $region = $params['region'] . (in_array($params['region'], ['ru', 'kr']) ? '' : '1');
        $url = str_replace('{region}', $region, self::API_URL).(
            isset($params['id']) ? '/'.$params['id'] : ''
        );
        return str_replace('{endpoint}', $apiEndpoint, $url);
    }
}

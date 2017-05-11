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
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return array Fetch response
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->attempts++;
        $request->requestFormat(Request::TYPE_JSON);
        try {
            $response = $this->client->get($this->buildQuery($request), [
                'query' => array_merge($request->where(), ['api_key' => $this->apiKey]),
                'headers' => [ 'Content-type' => 'application/json' ],
            ]);
        } catch (ServerException $e) {
            $this->log->error('Guzzle Server Exception:', [
                'status' => $e->getResponse()->getStatusCode(),
                'request' => $e->getRequest()->getUri(),
                'response' => $e->getResponse()->getBody(),
            ]);
            $response = $e->getResponse();
        } catch (ClientException $e) {
            $this->log->error('Guzzle Client Exception:', [
                'status' => $e->getResponse()->getStatusCode(),
                'request' => $e->getRequest()->getUri(),
                'response' => $e->getResponse()->getBody(),
            ]);
            $response = $e->getResponse();
        }
        $state = $this->checkResponse($response);
        switch ($state) {
            case self::API_PROCEED:
                $this->attempts = 0;
                return json_decode($response->getBody(), true);
            case self::API_REPEAT:
                sleep(1);

                return $this->fetch($request);
            case self::API_SKIP:
                $this->attempts = 0;
                return [];
            case self::API_FAIL:
                exit;
        }
    }

    /**
     * Insert object via API
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return int Insert response
     */
    public function insert(RequestInterface $request) : int
    {
        $this->log->error("API is read-only", ['request' => $request]);
        return -1;
    }

    /**
     * Update object via API
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return int Update response.
     */
    public function update(RequestInterface $request) : int
    {
        $this->log->error("API is read-only", ['request' => $request]);
        return -1;
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
     * @param RequestInterface $request
     * @return string
     */
    public function buildQuery(RequestInterface $request) : string
    {
        $params = $request->where();
        $region = $params['region'] . (in_array($params['region'], ['ru', 'kr']) ? '' : '1');
        $url = str_replace('{region}', $region, self::API_URL).(
            isset($params['id']) ? '/'.$params['id'] : ''
        );
        return str_replace('{endpoint}', $request->query(), $url);
    }
}

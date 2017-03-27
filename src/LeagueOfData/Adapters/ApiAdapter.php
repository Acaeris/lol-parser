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
    const API_PROCEED = 1;
    const API_SKIP = 2;
    const API_REPEAT = 3;
    const API_FAIL = 0;

    private $log;
    private $client;
    private $apiKey;

    /**
     * Set up the API adapter
     *
     * @param LoggerInterface $log
     * @param string          $apiKey
     */
    public function __construct(LoggerInterface $log, string $apiKey)
    {
        $this->log = $log;
        $this->client = new Client();
        $this->apiKey = $apiKey;
    }

    /**
     * Fetch data from API
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return array Fetch response
     */
    public function fetch(RequestInterface $request)
    {
        $request->requestFormat(RequestInterface::REQUEST_JSON);
        try {
            $response = $this->client->get($request->query(), [
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
                return json_decode($response->getBody());
            case self::API_REPEAT:
                sleep(1);

                return $this->fetch($request);
            case self::API_FAIL:
                exit;
            case self::API_SKIP:
                return false;
        }
    }

    /**
     * Insert object via API
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return stdClass Insert response
     */
    public function insert(RequestInterface $request)
    {
        $this->log->error("API is read-only");
    }

    /**
     * Update object via API
     *
     * @param LeagueOfData\Adapters\RequestInterface $request Request object
     *
     * @return stdClass Update response.
     */
    public function update(RequestInterface $request)
    {
        $this->log->error("API is read-only");
    }

    private function checkResponse(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 200:
                return self::API_PROCEED;
            case 503:
                $this->log->info("API unavailable. Waiting to retry.");

                return self::API_REPEAT;
            case 404:
                $this->log->info("Data unavailable. Skipping.");

                return self::API_SKIP;
            default:
                $this->log->info("Unknown response: ".$response->getStatusCode());

                return self::API_FAIL;
        }
    }
}

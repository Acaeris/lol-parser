<?php

namespace LeagueOfData\Adapters\API;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\API\Exception\APIException;

class ApiAdapter implements AdapterInterface
{
    const API_PROCEED = 1;
    const API_SKIP = 2;
    const API_REPEAT = 3;
    const API_FAIL = 0;
    
    private $log;
    private $client;
    private $apiKey;

    public function __construct(LoggerInterface $log, $apiKey)
    {
        $this->log = $log;
        $this->client = new Client();
        $this->apiKey = $apiKey;
    }

    public function fetch($type, $data)
    {
        $request = $this->buildRequest($type, $data);
        $this->log->debug('<info>URL:</> ' . $request->call());
        return $this->request($request);
    }

    public function insert($type, $data)
    {
        $this->log->error("API is read-only");
    }

    public function update($type, $data, $where) {
        $this->log->error("API is read-only");
    }

    private function request(Request $request)
    {
        try {
            $response = $this->client->get($request->call(), [
                'query' => array_merge($request->params(), ['api_key' => $this->apiKey]),
                'headers' => [
                    'Content-type' => 'application/json'
                ]
            ]);
        } catch (ServerException $e) {
            $this->log->error('Guzzle Server Exception:', ['status' => $e->getResponse()->getStatusCode(),
                'request' => $e->getRequest()->getUri(), 'response' => $e->getResponse()->getBody()]);
            $response = $e->getResponse();
        } catch (ClientException $e) {
            $this->log->error('Guzzle Client Exception:', ['status' => $e->getResponse()->getStatusCode(),
                'request' => $e->getRequest()->getUri(), 'response' => $e->getResponse()->getBody()]);
            $response = $e->getResponse();
        }
        $state = $this->checkResponse($response);
        switch ($state) {
            case self::API_PROCEED:
                return json_decode($response->getBody());
            case self::API_REPEAT:
                sleep(1);
                return $this->request($request);
            case self::API_FAIL:
                exit;
            case self::API_SKIP:
                return false;
        } 
    }

    private function buildRequest($type, $data)
    {
        switch ($type) {
            case 'champion' :
                return new ChampionRequest($data);
            case 'summoner' :
                return new SummonerRequest($data);
            case 'matchList' :
                return new MatchListRequest($data);
            case 'version' :
                return new VersionRequest($data);
            case 'item' :
                return new ItemRequest($data);
            default:
                throw new APIException("Request type unavailable: " . $type);
        }
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
                $this->log->info("Unknown response: " . $response->getStatusCode());
                return self::API_FAIL;
        }
    }
}

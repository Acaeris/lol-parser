<?php

namespace LeagueOfData\Adapters\API;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\API\Exception\APIException;

class ApiAdapter implements AdapterInterface
{
    private $log;
    private $apiKey;

    public function __construct(LoggerInterface $log, $apiKey)
    {
        $this->log = $log;
        $this->apiKey = $apiKey;
    }

    public function fetch($type, $data)
    {
        $this->log->info('Calling API');
        $request = $this->buildRequest($type, $data);
        $this->log->debug('<info>URL:</> ' . $request->call());
        return $this->request($request);
    }

    public function insert($type, $data)
    {
        $this->log->error("Cannot insert into API.");
    }

    public function update($type, $data, $where) {
        $this->log->error("Cannot update the API.");
    }

    private function request(Request $request)
    {
        $ch = curl_init();
        $url = $request->call() . '?api_key=' . $apiKey;
        foreach ($request->params() as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }
        curl_setopt($ch, CURLOPT_URL, $url); // Store this in config
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        // Store response...?
        $response = json_decode($body);
        if ($this->processStatus($header, $response)) {
            return $response;
        } else {
            sleep(1);
            return $this->request($request);
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
            default:
                throw new APIException("Request type unavailable: " . $type);
        }
    }

    private function processStatus($header, $response)
    {
        if (isset($response->status)) {
            switch ($response->status->status_code) {
                case 503 :
                    $this->log->error("Service unavailable. Waiting to retry");
                    return false;
                default:
                    $this->log->info("Response Status [{$response->status->status_code}]: {$response->status->message}");
                    exit;
            }
        }
        return true;
    }
}

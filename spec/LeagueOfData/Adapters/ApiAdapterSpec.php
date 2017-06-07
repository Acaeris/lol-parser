<?php

namespace spec\LeagueOfData\Adapters;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use LeagueOfData\Adapters\RequestInterface;

class ApiAdapterSpec extends ObjectBehavior
{
    public function let(LoggerInterface $log, Client $client)
    {
        $this->beConstructedWith($log, $client, 'TEST');
    }

    public function it_should_fail_an_insert_call(LoggerInterface $log, RequestInterface $request)
    {
        $log->error("API is read-only", ['request' => $request])->shouldBeCalled();
        $this->insert($request)->shouldReturn(-1);
    }

    public function it_should_fail_an_update_call(LoggerInterface $log, RequestInterface $request)
    {
        $log->error("API is read-only", ['request' => $request])->shouldBeCalled();
        $this->update($request)->shouldReturn(-1);
    }

    public function it_can_build_the_api_query(RequestInterface $request)
    {
        $request->where()->willReturn([
            'region' => 'na',
            'champListData' => 'all',
            'id' => 1
        ]);
        $request->query()->willReturn('static/v3/champions');
        $this->buildQuery($request)->shouldReturn('https://na1.api.riotgames.com/lol/static/v3/champions/1');
    }

    public function it_can_process_the_api_response_code_200(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->checkResponse($response)->shouldReturn(1);
    }

    public function it_can_process_the_api_response_code_503(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(503);
        $this->checkResponse($response)->shouldReturn(3);
    }

    public function it_can_process_the_api_response_code_404(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(404);
        $this->checkResponse($response)->shouldReturn(2);
    }

    public function it_can_request_guzzle_fetch_data_from_the_api(Client $client, RequestInterface $request,
        ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn('{"test":{"object":1}}');
        $client->get('https://na1.api.riotgames.com/lol/static/v3/champions/1',
            [
                'query' => [
                    'region' => 'na',
                    'champListData' => 'all',
                    'id' => 1,
                    'api_key' => 'TEST'
                ],
                'headers' => ['Content-type' => 'application/json']
            ])->willReturn($response);
        $request->where()->willReturn([
            'region' => 'na',
            'champListData' => 'all',
            'id' => 1
        ]);
        $request->requestFormat('json')->shouldBeCalled();
        $request->query()->willReturn('static/v3/champions');
        $this->fetch($request)->shouldReturn(["test" => [ "object" => 1]]);
    }
}
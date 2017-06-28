<?php
namespace spec\LeagueOfData\Adapters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class ApiAdapterSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger, ClientInterface $client)
    {
        $this->beConstructedWith($logger, $client, 'test');
    }

    public function it_can_build_the_API_query()
    {
        $this->setOptions('static-data/v3/versions', ['region' => 'euw'])->buildQuery()
            ->shouldReturn('https://euw1.api.riotgames.com/lol/static-data/v3/versions');
    }

    public function it_checks_the_response_status_200(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn('{"test": "Test"}');
        $this->checkResponse($response)->shouldReturn(['test' => 'Test']);
    }

    public function it_checks_the_response_status_503(
        ClientInterface $client,
        ResponseInterface $response,
        LoggerInterface $logger)
    {
        $this->setOptions("Test", ['region' => 'euw']);
        $response->getStatusCode()->willReturn(503);
        $client->request(Argument::cetera())->willReturn($response);
        $logger->info("API unavailable. Waiting to retry.")->shouldBeCalled();
        $logger->info("API unavailable after 3 attempts. Skipping.")->shouldBeCalled();
        $this->checkResponse($response)->shouldReturn([]);
    }

    public function it_checks_the_response_status_404(ResponseInterface $response, LoggerInterface $logger)
    {
        $response->getStatusCode()->willReturn(404);
        $logger->info("Data unavailable. Skipping.")->shouldBeCalled();
        $this->checkResponse($response)->shouldReturn([]);
    }

    public function it_checks_unknown_response_code(ResponseInterface $response, LoggerInterface $logger)
    {
        $response->getStatusCode()->willReturn(0);
        $logger->error("Unknown response: 0")->shouldBeCalled();
        $this->checkResponse($response)->shouldReturn([]);
    }

    public function it_calls_the_api_for_data(ClientInterface $client, ResponseInterface $response)
    {
        $client->request('GET', 'https://euw1.api.riotgames.com/lol/static-data/v3/versions', Argument::cetera())
            ->willReturn($response);
        $this->setOptions('static-data/v3/versions', ['region' => 'euw'])->fetch();
    }
}

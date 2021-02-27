<?php

namespace Tests\Unit\Http\Clients\DicionarioAberto;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientException;
use App\Http\Clients\DicionarioAberto\LiveDicionarioAbertoClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class LiveDicionarioAbertoClientTest extends TestCase
{
    private function getDicionarioAbertoClientMock(array $responses = []): LiveDicionarioAbertoClient
    {
        return new LiveDicionarioAbertoClient(
            new GuzzleClient([
                'handler' => HandlerStack::create(new MockHandler($responses)),
            ]),
        );
    }

    /** @test */
    public function it_should_successfully_retrieve_a_json_response(): void
    {
        $client = $this->getDicionarioAbertoClientMock([
            new Response(200, [], '[]'),
        ]);

        $response = $client->search('word');

        $this->assertEquals([], $response);
    }

    /** @test */
    public function it_should_have_an_exception_if_the_client_throws_an_error(): void
    {
        $this->expectException(DicionarioAbertoClientException::class);
        $this->expectExceptionCode(DicionarioAbertoClientException::CODE_SEARCH_FAILURE);

        $client = $this->getDicionarioAbertoClientMock([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        $client->search('word');
    }

    /** @test */
    public function it_should_have_an_exception_if_the_json_cannot_be_parsed(): void
    {
        $this->expectException(DicionarioAbertoClientException::class);
        $this->expectExceptionCode(DicionarioAbertoClientException::CODE_SEARCH_FAILURE);

        $client = $this->getDicionarioAbertoClientMock([
            new Response(200, [], ''),
        ]);

        $client->search('word');
    }
}

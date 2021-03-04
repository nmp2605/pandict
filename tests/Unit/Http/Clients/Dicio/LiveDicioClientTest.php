<?php

namespace Tests\Unit\Http\Clients\Dicio;

use App\Http\Clients\Dicio\DicioClientException;
use App\Http\Clients\Dicio\LiveDicioClient;
use DOMDocument;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class LiveDicioClientTest extends TestCase
{
    private function getDicionarioAbertoClientMock(array $responses = []): LiveDicioClient
    {
        return new LiveDicioClient(
            new GuzzleClient([
                'handler' => HandlerStack::create(new MockHandler($responses)),
            ]),
        );
    }

    /** @test */
    public function it_should_successfully_retrieve_the_html(): void
    {
        $client = $this->getDicionarioAbertoClientMock([
            new Response(200, [], '<html>Content</html>'),
        ]);

        $response = $client->search('word');

        $this->assertInstanceOf(DOMDocument::class, $response);
        $this->assertEquals('Content', $response->textContent);
    }

    /** @test */
    public function it_should_successfully_get_malformed_html(): void
    {
        $client = $this->getDicionarioAbertoClientMock([
            new Response(200, [], '<html>l>'),
        ]);

        $response = $client->search('word');

        $this->assertInstanceOf(DOMDocument::class, $response);
        $this->assertEquals('l>', $response->textContent);
    }

    /** @test */
    public function it_should_have_an_exception_if_the_client_throws_an_error(): void
    {
        $this->expectException(DicioClientException::class);
        $this->expectExceptionCode(DicioClientException::CODE_SEARCH_FAILURE);

        $client = $this->getDicionarioAbertoClientMock([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        $client->search('word');
    }

    /** @test */
    public function it_should_have_an_exception_if_the_html_is_empty(): void
    {
        $this->expectException(DicioClientException::class);
        $this->expectExceptionCode(DicioClientException::CODE_SEARCH_FAILURE);

        $client = $this->getDicionarioAbertoClientMock([
            new Response(200, [], ''),
        ]);

        $client->search('word');
    }
}

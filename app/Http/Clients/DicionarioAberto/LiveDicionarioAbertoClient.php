<?php

namespace App\Http\Clients\DicionarioAberto;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use JsonException;
use RuntimeException;

class LiveDicionarioAbertoClient implements DicionarioAbertoClientInterface
{
    private GuzzleClient $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function search(string $word): array
    {
        try {
            $response = $this->client->get("/word/{$word}");
        } catch (RequestException $exception) {
            throw DicionarioAbertoClientException::searchFailure($word, $exception);
        }

        try {
            $responseBody = $response->getBody()->getContents();
        } catch (RuntimeException $exception) {
            throw DicionarioAbertoClientException::searchFailure($word, $exception);
        }

        try {
            $parsedResponse = json_decode($responseBody, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw DicionarioAbertoClientException::searchFailure($word, $exception);
        }

        return $parsedResponse;
    }
}

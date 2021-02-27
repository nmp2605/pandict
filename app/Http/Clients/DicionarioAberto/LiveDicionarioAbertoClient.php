<?php

namespace App\Http\Clients\DicionarioAberto;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

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
        } catch (RequestException $e) {
            throw DicionarioAbertoClientException::searchFailure($word, $e);
        }

        $parsedResponse = json_decode(
            $response->getBody()->getContents()
        );

        if ($parsedResponse === null) {
            throw DicionarioAbertoClientException::searchFailure($word);
        }

        return $parsedResponse;
    }
}

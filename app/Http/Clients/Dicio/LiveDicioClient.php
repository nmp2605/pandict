<?php

namespace App\Http\Clients\Dicio;

use DOMDocument;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class LiveDicioClient implements DicioClientInterface
{
    private GuzzleClient $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function search(string $word): DOMDocument
    {
        try {
            $response = $this->client->get("/{$word}");
        } catch (RequestException $e) {
            throw DicioClientException::searchFailure($word, $e);
        }

        $responseContents = $response->getBody()->getContents();

        if (empty($responseContents)) {
            throw DicioClientException::searchFailure($word);
        }

        $document = new DOMDocument;

        libxml_use_internal_errors(true);

        if ($document->loadHTML($responseContents) === false) {
            throw DicioClientException::searchFailure($word);
        }

        libxml_use_internal_errors(false);

        return $document;
    }
}

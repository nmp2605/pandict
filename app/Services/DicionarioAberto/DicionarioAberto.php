<?php

namespace App\Services\DicionarioAberto;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientException;
use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Models\Result;
use App\Parsers\Result\DicionarioAberto\ParseDicionarioAbertoResult;
use App\Services\DictionaryServiceInterface;
use Illuminate\Support\Collection;

class DicionarioAberto implements DictionaryServiceInterface
{
    public function __construct(
        private DicionarioAbertoClientInterface $client,
        private ParseDicionarioAbertoResult $parser
    ) {
    }

    /**
     * Searches for a word using DicionarioAberto's service.
     *
     * @throws DicionarioAbertoException
     */
    public function search(string $word): Collection
    {
        try {
            $results = $this->client->search($word);
        } catch (DicionarioAbertoClientException $exception) {
            throw DicionarioAbertoException::clientFailure($exception);
        }

        return Collection::make($results)
            ->map(function (object $result) use ($word): Result {
                return $this->parser->handle($word, $result);
            });
    }
}

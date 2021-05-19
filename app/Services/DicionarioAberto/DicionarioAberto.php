<?php

namespace App\Services\DicionarioAberto;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientException;
use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Models\Result;
use App\Parsers\DicionarioAberto\ParseDicionarioAbertoResult;
use Illuminate\Support\Collection;

class DicionarioAberto
{
    public function __construct(
        private DicionarioAbertoClientInterface $client,
        private ParseDicionarioAbertoResult $parser
    ) {
    }

    /** Searches for a word using DicionarioAberto's service. */
    public function search(string $word): Collection
    {
        try {
            $results = $this->dicionarioAberto->search($word);
        } catch (DicionarioAbertoClientException) {
            return Collection::make();
        }

        return Collection::make($results)
            ->map(fn (object $result): Result => $this->parser->handle($word, $result));
    }
}

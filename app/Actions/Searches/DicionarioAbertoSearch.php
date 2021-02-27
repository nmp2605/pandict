<?php

namespace App\Actions\Searches;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientException;
use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Models\Result;
use App\Parsers\DicionarioAberto\ParseDicionarioAbertoResult;
use Illuminate\Support\Collection;

class DicionarioAbertoSearch
{
    private DicionarioAbertoClientInterface $dicionarioAberto;
    private ParseDicionarioAbertoResult $parseDicionarioAbertoResult;

    public function __construct(DicionarioAbertoClientInterface $dicionarioAberto, ParseDicionarioAbertoResult $parseDicionarioAbertoResult)
    {
        $this->dicionarioAberto = $dicionarioAberto;
        $this->parseDicionarioAbertoResult = $parseDicionarioAbertoResult;
    }

    public function __invoke(string $word): Collection
    {
        try {
            $response = $this->dicionarioAberto->search($word);
        } catch (DicionarioAbertoClientException $e) {
            return Collection::make();
        }

        return $this->parseResults($response);
    }

    private function parseResults(array $results): Collection
    {
        return Collection::make($results)
            ->map(fn (object $result): Result => ($this->parseDicionarioAbertoResult)($result));
    }
}

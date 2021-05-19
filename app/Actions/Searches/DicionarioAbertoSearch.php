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

    public function handle(string $word): Collection
    {
        try {
            $results = $this->dicionarioAberto->search($word);
        } catch (DicionarioAbertoClientException) {
            return Collection::make();
        }

        return Collection::make($results)
            ->map(fn (object $result): Result => $this->parseDicionarioAbertoResult->handle($result));
    }
}

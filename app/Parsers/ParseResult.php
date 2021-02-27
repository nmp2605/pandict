<?php

namespace App\Parsers;

use App\Models\Result;
use Illuminate\Support\Collection;

abstract class ParseResult
{
    public function __invoke(object $result): Result
    {
        return Result::make([
            'details' => $this->parseDetails($result),
            'entries' => $this->parseEntries($result),
            'source' => $this->parseSource($result),
        ]);
    }

    abstract public function parseDetails(object $result): Collection;

    abstract public function parseEntries(object $result): Collection;

    abstract public function parseSource(object $result): string;
}

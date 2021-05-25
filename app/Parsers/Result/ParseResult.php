<?php

namespace App\Parsers\Result;

use App\Models\Result;
use App\Parsers\ParserInterface;
use Illuminate\Support\Collection;

abstract class ParseResult implements ParserInterface
{
    /** {@inheritdoc} */
    public function handle(string $word, object $result): Result
    {
        return Result::make([
            'details' => $this->parseDetails($result),
            'entries' => $this->parseEntries($result),
            'source_name' => $this->parseSourceName($result),
            'source_url' => $this->parseSourceUrl($word, $result),
        ]);
    }

    abstract public function parseDetails(object $result): Collection;

    abstract public function parseEntries(object $result): Collection;

    abstract public function parseSourceName(object $result): string;

    abstract public function parseSourceUrl(string $word, object $result): string;
}

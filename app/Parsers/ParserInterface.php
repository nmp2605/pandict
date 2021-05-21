<?php

namespace App\Parsers;

use Illuminate\Database\Eloquent\Model;

interface ParserInterface
{
    /** Parses vendor data into a model. */
    public function handle(string $word, object $result): Model;
}

<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Support\Collection;

interface DictionaryServiceInterface
{
    /**
     * Searches for a word on a dictionary service.
     *
     * @return Collection<Result>
     */
    public function search(string $word): Collection;
}

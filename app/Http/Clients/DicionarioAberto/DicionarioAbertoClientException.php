<?php

namespace App\Http\Clients\DicionarioAberto;

use Exception;
use Throwable;

class DicionarioAbertoClientException extends Exception
{
    public const CODE_SEARCH_FAILURE = 1;

    public static function searchFailure(string $word, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Failed to search for word "%s"', $word), self::CODE_SEARCH_FAILURE, $previous
        );
    }
}

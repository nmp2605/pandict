<?php

namespace App\Services\Dicio;

use Exception;
use Throwable;

class DicioException extends Exception
{
    public const CODE_CLIENT_FAILURE = 0;
    public const CODE_NO_RESULT_CLASS_FOUND_FAILURE = 1;

    public static function clientFailure(?Throwable $previous = null): self
    {
        return new self('Failed to fetch a result page from the client.', self::CODE_CLIENT_FAILURE, $previous);
    }

    public static function noResultClassFoundFailure(string $word, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Failed to find "significado textonovo" classes on result page for word "%s"', $word), self::CODE_NO_RESULT_CLASS_FOUND_FAILURE, $previous
        );
    }
}

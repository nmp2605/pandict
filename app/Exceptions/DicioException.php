<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DicioException extends Exception
{
    public const CODE_NO_RESULT_CLASS_FOUND_FAILURE = 1;

    public static function noResultClassFoundFailure(string $word, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Failed to find "significado textonovo" classes on result page for word "%s"', $word), self::CODE_NO_RESULT_CLASS_FOUND_FAILURE, $previous
        );
    }
}

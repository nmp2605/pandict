<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ParsingException extends Exception
{
    public const CODE_DICIONARIO_ABERTO_FAILURE = 1;

    public static function dicionarioAbertoFailure(?Throwable $previous = null): self
    {
        return new self('Failed parsing a DicionarioAberto result.', self::CODE_DICIONARIO_ABERTO_FAILURE, $previous);
    }
}

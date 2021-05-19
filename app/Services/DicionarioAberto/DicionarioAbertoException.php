<?php

namespace App\Services\DicionarioAberto;

use Exception;
use Throwable;

class DicionarioAbertoException extends Exception
{
    private const CODE_CLIENT_FAILURE = 0;

    public static function clientFailure(?Throwable $previous = null): self
    {
        return new self('Failed to fetch data from the client.', self::CODE_CLIENT_FAILURE, $previous);
    }
}

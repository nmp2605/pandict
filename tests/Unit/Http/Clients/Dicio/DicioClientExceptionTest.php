<?php

namespace Tests\Unit\Http\Clients\Dicio;

use App\Http\Clients\Dicio\DicioClientException;
use Exception;
use PHPUnit\Framework\TestCase;

class DicioClientExceptionTest extends TestCase
{
    /** @test */
    public function it_should_get_a_search_failure(): void
    {
        $previousException = new Exception('Test exception');

        $exception = DicioClientException::searchFailure('word', $previousException);

        $this->assertEquals('Failed to search for word "word"', $exception->getMessage());
        $this->assertEquals(DicioClientException::CODE_SEARCH_FAILURE, $exception->getCode());
        $this->assertEquals($previousException, $exception->getPrevious());
    }
}

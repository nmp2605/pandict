<?php

namespace Tests\Unit\Http\Clients\DicionarioAberto;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientException;
use Exception;
use Tests\TestCase;

class DicionarioAbertoClientExceptionTest extends TestCase
{
    /** @test */
    public function it_should_get_a_search_failure(): void
    {
        $previousException = new Exception('Test exception');

        $exception = DicionarioAbertoClientException::searchFailure('word', $previousException);

        $this->assertEquals('Failed to search for word "word"', $exception->getMessage());
        $this->assertEquals(DicionarioAbertoClientException::CODE_SEARCH_FAILURE, $exception->getCode());
        $this->assertEquals($previousException, $exception->getPrevious());
    }
}

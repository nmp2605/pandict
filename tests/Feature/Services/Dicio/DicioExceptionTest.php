<?php

namespace Tests\Feature\Services\Dicio;

use App\Services\Dicio\DicioException;
use Exception;
use Tests\TestCase;

class DicioExceptionTest extends TestCase
{
    /** @test */
    public function it_should_get_a_client_failure(): void
    {
        $previousException = new Exception('Test exception');

        $exception = DicioException::clientFailure($previousException);

        $this->assertEquals('Failed to fetch a result page from the client.', $exception->getMessage());
        $this->assertEquals(DicioException::CODE_CLIENT_FAILURE, $exception->getCode());
        $this->assertEquals($previousException, $exception->getPrevious());
    }

    /** @test */
    public function it_should_get_a_no_result_class_found_failure(): void
    {
        $previousException = new Exception('Test exception');

        $exception = DicioException::noResultClassFoundFailure('word', $previousException);

        $this->assertEquals('Failed to find "significado textonovo" classes on result page for word "word"', $exception->getMessage());
        $this->assertEquals(DicioException::CODE_NO_RESULT_CLASS_FOUND_FAILURE, $exception->getCode());
        $this->assertEquals($previousException, $exception->getPrevious());
    }
}

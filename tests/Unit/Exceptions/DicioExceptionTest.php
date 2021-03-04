<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\DicioException;
use Exception;
use PHPUnit\Framework\TestCase;

class DicioExceptionTest extends TestCase
{
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

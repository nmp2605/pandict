<?php

namespace Tests\Feature\Services\DicionarioAberto;

use App\Services\DicionarioAberto\DicionarioAbertoException;
use Exception;
use Tests\TestCase;

class DicionarioAbertoExceptionTest extends TestCase
{
    /** @test */
    public function it_should_get_a_client_failure(): void
    {
        $previousException = new Exception('Test exception');

        $exception = DicionarioAbertoException::clientFailure($previousException);

        $this->assertEquals('Failed to fetch data from the client.', $exception->getMessage());
        $this->assertEquals(DicionarioAbertoException::CODE_CLIENT_FAILURE, $exception->getCode());
        $this->assertEquals($previousException, $exception->getPrevious());
    }
}

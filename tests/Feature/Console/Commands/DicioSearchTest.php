<?php

namespace Tests\Feature\Console\Commands;

use App\Services\Dicio\Dicio;
use App\Services\Dicio\DicioException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Testing\PendingCommand;
use Mockery\MockInterface;
use Tests\TestCase;

class DicioSearchTest extends TestCase
{
    /** @var Dicio&MockInterface */
    private $dicio;

    public function setUp(): void
    {
        parent::setUp();

        $this->dicio = $this->mock(Dicio::class);
    }

    /** @test */
    public function it_should_show_search_results(): void
    {
        $this->dicio->shouldReceive('search')
            ->once()
            ->andReturn(Collection::make());

        /** @var PendingCommand $command */
        $command = $this->artisan('dicio:search word');
        $command->expectsOutput('[]')->assertExitCode(Command::SUCCESS);
    }

    /** @test */
    public function it_should_handle_a_service_exception(): void
    {
        $this->dicio->shouldReceive('search')
            ->once()
            ->andThrow(new DicioException('Exception message.'));

        /** @var PendingCommand $command */
        $command = $this->artisan('dicio:search word');
        $command->expectsOutput('Exception message.')->assertExitCode(Command::FAILURE);
    }
}

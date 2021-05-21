<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;
use RuntimeException;
use Mockery\MockInterface;
use Illuminate\Testing\PendingCommand;
use App\Services\DicionarioAberto\DicionarioAberto;
use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Services\DicionarioAberto\DicionarioAbertoException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DicionarioAbertoSearchTest extends TestCase
{
    /** @var DicionarioAberto&MockInterface */
    private $dicionarioAberto;

    public function setUp(): void
    {
        parent::setUp();

        $this->dicionarioAberto = $this->mock(DicionarioAberto::class);
    }

    /** @test */
    public function it_should_show_search_results(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->once()
            ->andReturn(Collection::make());

        /** @var PendingCommand $command */
        $command = $this->artisan('dicionario-aberto:search word');
        $command->expectsOutput('[]')->assertExitCode(Command::SUCCESS);
    }

    /** @test */
    public function it_should_handle_a_service_exception(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->once()
            ->andThrow(new DicionarioAbertoException('Exception message.'));

        /** @var PendingCommand $command */
        $command = $this->artisan('dicionario-aberto:search word');
        $command->expectsOutput('Exception message.')->assertExitCode(Command::FAILURE);
    }
}

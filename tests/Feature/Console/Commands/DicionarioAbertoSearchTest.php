<?php

namespace Tests\Feature\Console\Commands;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class DicionarioAbertoSearchTest extends TestCase
{
    /** @var DicionarioAbertoClientInterface&MockInterface */
    private $dicionarioAberto;

    public function setUp(): void
    {
        parent::setUp();

        $this->dicionarioAberto = $this->mock(DicionarioAbertoClientInterface::class);
    }

    /** @test */
    public function it_should_search_for_a_word_using_dicionario_aberto(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn([
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <gramGrp>f.</gramGrp>
                            <usg type="style">Ant.</usg>
                            <def>This is an entry\nThis is another one</def>
                        </sense>
                        <etym orig="Lat">(Lat. _word_)</etym>
                    </entry>
                    XML,
                ],
            ]);

        $command = $this->artisan('dicionario-aberto:search word');

        if (is_int($command)) {
            throw new RuntimeException('The expected command is an integer.');
        }

        $command->expectsOutput('gênero: f.')
            ->expectsOutput('uso: Ant.')
            ->expectsOutput('etimologia: (Lat. word)')
            ->expectsOutput("\n")
            ->expectsOutput('- This is an entry')
            ->expectsOutput('- This is another one')
            ->expectsOutput("\n")
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_show_more_than_one_result(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn([
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <def>This is from the first result</def>
                        </sense>
                    </entry>
                    XML,
                ],
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <def>This is from the second result</def>
                        </sense>
                    </entry>
                    XML,
                ],
            ]);

        $command = $this->artisan('dicionario-aberto:search word');

        if (is_int($command)) {
            throw new RuntimeException('The expected command is an integer.');
        }

        $command->expectsOutput('- This is from the first result')
            ->expectsOutput("\n")
            ->expectsOutput('- This is from the second result')
            ->expectsOutput("\n")
            ->assertExitCode(0);
    }
}

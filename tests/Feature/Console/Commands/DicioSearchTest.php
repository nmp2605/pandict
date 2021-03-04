<?php

namespace Tests\Feature\Console\Commands;

use App\Http\Clients\Dicio\DicioClientInterface;
use DOMDocument;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class DicioSearchTest extends TestCase
{
    /** @var DicioClientInterface&MockInterface */
    private $dicio;

    public function setUp(): void
    {
        parent::setUp();

        $this->dicio = $this->mock(DicioClientInterface::class);
    }

    /** @test */
    public function it_should_search_for_a_word_using_dicionario_aberto(): void
    {
        $document = new DOMDocument;

        $document->loadHTML(<<<HTML
        <!DOCTYPE html>
        <html lang="pt-br" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
            <head>
                <meta charset="utf-8">
                <title>word - Dicio, Dicionário Online de Português</title>
            </head>
            <body itemscope itemtype="http://schema.org/WebPage">
                <div id="wrapper">
                    <div class="container">
                        <div id="content" class="row mt20--d" data-sticky-container>
                            <div class="col-xs-12 col-sm-7 col-md-8 parent-card">
                                <div class="card">
                                    <div class="box-social"></div>
                                    <h1 itemprop="name">word</h1>
                                    <h2 class="tit-significado">Significado de word</h2>
                                    <p itemprop="description" class="significado textonovo">
                                        <span class="cl">Class</span>
                                        <span>This is an entry</span>
                                        <span>This is another one</span>
                                        <br/>
                                        <span class="etim">Etymology</span>
                                        <br/>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        HTML);

        $this->dicio->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn($document);

        $command = $this->artisan('dicio:search word');

        if (is_int($command)) {
            throw new RuntimeException('The expected command is an integer.');
        }

        $command->expectsOutput('classe gramatical: Class')
            ->expectsOutput('etimologia: Etymology')
            ->expectsOutput("\n")
            ->expectsOutput('- This is an entry')
            ->expectsOutput('- This is another one')
            ->expectsOutput("\n")
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_show_more_than_one_result(): void
    {
        $document = new DOMDocument;

        $document->loadHTML(<<<HTML
        <!DOCTYPE html>
        <html lang="pt-br" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
            <head>
                <meta charset="utf-8">
                <title>word - Dicio, Dicionário Online de Português</title>
            </head>
            <body itemscope itemtype="http://schema.org/WebPage">
                <div id="wrapper">
                    <div class="container">
                        <div id="content" class="row mt20--d" data-sticky-container>
                            <div class="col-xs-12 col-sm-7 col-md-8 parent-card">
                                <div class="card">
                                    <div class="box-social"></div>
                                    <h1 itemprop="name">word</h1>
                                    <h2 class="tit-significado">Significado de word</h2>
                                    <p itemprop="description" class="significado textonovo">
                                        <span class="cl">Class</span>
                                        <span>This is an entry</span>
                                        <span>This is another one</span>
                                        <br/>
                                        <span class="etim">Etymology</span>
                                        <br/>
                                        <span class="cl">Other</span>
                                        <span>One more time</span>
                                        <br/>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        HTML);

        $this->dicio->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn($document);

        $command = $this->artisan('dicio:search word');

        if (is_int($command)) {
            throw new RuntimeException('The expected command is an integer.');
        }

        $command->expectsOutput('classe gramatical: Class')
            ->expectsOutput('etimologia: Etymology')
            ->expectsOutput("\n")
            ->expectsOutput('- This is an entry')
            ->expectsOutput('- This is another one')
            ->expectsOutput("\n")
            ->expectsOutput('classe gramatical: Other')
            ->expectsOutput("\n")
            ->expectsOutput('- One more time')
            ->expectsOutput("\n")
            ->assertExitCode(0);
    }
}

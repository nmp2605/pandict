<?php

namespace Tests\Feature\Actions\Searches;

use App\Http\Clients\Dicio\DicioClientInterface;
use App\Models\Result;
use App\Services\Dicio\Dicio;
use DOMDocument;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class DicioSearchTest extends TestCase
{
    /** @var DicioClientInterface&MockInterface */
    private $client;
    private Dicio $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->mock(DicioClientInterface::class);
        $this->service = $this->app->make(Dicio::class);
    }

    /** @test */
    public function it_should_return_a_single_result(): void
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

        $this->client->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn($document);

        $results = $this->service->search('word');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf(Result::class, $results[0]);

        $this->assertEquals(['name' => 'classe gramatical', 'value' => 'Class'], $results[0]->details[0]);
        $this->assertEquals(['name' => 'etimologia', 'value' => 'Etymology'], $results[0]->details[1]);
        $this->assertEquals('This is an entry', $results[0]->entries[0]);
        $this->assertEquals('This is another one', $results[0]->entries[1]);
    }

    /** @test */
    public function it_should_return_more_than_one_result(): void
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
                                        <span>More entries</span>
                                        <span>One more time</span>
                                        <br/>
                                        <span class="etim">Other Etymology</span>
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

        $this->client->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn($document);

        $results = $this->service->search('word');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf(Result::class, $results[0]);

        $this->assertEquals(['name' => 'classe gramatical', 'value' => 'Class'], $results[0]->details[0]);
        $this->assertEquals(['name' => 'etimologia', 'value' => 'Etymology'], $results[0]->details[1]);
        $this->assertEquals('This is an entry', $results[0]->entries[0]);
        $this->assertEquals('This is another one', $results[0]->entries[1]);

        $this->assertEquals(['name' => 'classe gramatical', 'value' => 'Other'], $results[1]->details[0]);
        $this->assertEquals(['name' => 'etimologia', 'value' => 'Other Etymology'], $results[1]->details[1]);
        $this->assertEquals('More entries', $results[1]->entries[0]);
        $this->assertEquals('One more time', $results[1]->entries[1]);
    }
}

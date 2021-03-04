<?php

namespace App\Http\Clients\Dicio;

use DOMDocument;
use Faker\Generator;
use Illuminate\Support\Collection;

class MockDicioClient implements DicioClientInterface
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function search(string $word): DOMDocument
    {
        $results = Collection::make([0, 1, 2])
            ->map(fn (): string => $this->generateResultForWord())
            ->join("\n");

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
            <head>
                <meta charset="utf-8">
                <title>{$word} - Dicio, Dicionário Online de Português</title>
            </head>
            <body itemscope itemtype="http://schema.org/WebPage">
                <div id="wrapper">
                    <div class="container">
                        <div id="content" class="row mt20--d" data-sticky-container>
                            <div class="col-xs-12 col-sm-7 col-md-8 parent-card">
                                <div class="card">
                                    <div class="box-social"></div>
                                    <h1 itemprop="name">{$word}</h1>
                                    <h2 class="tit-significado">Significado de {$word}</h2>
                                    <p itemprop="description" class="significado textonovo">{$results}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        HTML;

        $document = new DOMDocument;

        if ($document->loadHTML($html) === false) {
            throw DicioClientException::searchFailure($word);
        }

        return $document;
    }

    private function generateResultForWord(): string
    {
        return <<<HTML
        <span class="cl">{$this->faker->word}</span>
        <span>{$this->faker->sentence}</span>
        <span>{$this->faker->sentence}</span>
        <br/>
        <span class="etim">{$this->faker->sentence}</span>
        <br/>
        HTML;
    }
}

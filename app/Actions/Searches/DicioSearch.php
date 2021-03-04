<?php

namespace App\Actions\Searches;

use App\Exceptions\DicioException;
use App\Http\Clients\Dicio\DicioClientException;
use App\Http\Clients\Dicio\DicioClientInterface;
use App\Models\Result;
use App\Parsers\Dicio\ParseDicioResult;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Illuminate\Support\Collection;

class DicioSearch
{
    private DicioClientInterface $dicio;
    private ParseDicioResult $parseDicioResult;

    public function __construct(DicioClientInterface $dicio, ParseDicioResult $parseDicioResult)
    {
        $this->dicio = $dicio;
        $this->parseDicioResult = $parseDicioResult;
    }

    public function handle(string $word): Collection
    {
        try {
            $response = $this->dicio->search($word);
        } catch (DicioClientException $e) {
            return Collection::make();
        }

        try {
            return $this->parseResults($response, $word);
        } catch (DicioException $e) {
            return Collection::make();
        }
    }

    private function parseResults(DOMDocument $resultPage, string $word): Collection
    {
        return Collection::make($this->getResultNodes($resultPage, $word))
            ->filter(fn (object $element): bool => $this->isResultStart($element))
            ->map(fn (DOMElement $element): Result => $this->parseDicioResult->handle($element))
            ->values();
    }

    private function getResultNodes(DOMDocument $resultsPage, string $word): DOMNodeList
    {
        $xpath = new DOMXPath($resultsPage);
        $resultNodes = $xpath->query("//*[@class='significado textonovo']");

        if ($resultNodes === false || $resultNodes->length !== 1) {
            throw DicioException::noResultClassFoundFailure($word);
        }

        $resultNode = $resultNodes->item(0);

        if ($resultNode === null) {
            throw DicioException::noResultClassFoundFailure($word);
        }

        return $resultNode->childNodes;
    }

    private function isResultStart(object $element): bool
    {
        return $element instanceof DOMElement
            && $element->nodeName === 'span'
            && $element->getAttribute('class') === 'cl';
    }
}

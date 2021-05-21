<?php

namespace App\Services\Dicio;

use App\Http\Clients\Dicio\DicioClientException;
use App\Http\Clients\Dicio\DicioClientInterface;
use App\Models\Result;
use App\Parsers\Result\Dicio\ParseDicioResult;
use App\Services\DictionaryServiceInterface;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Illuminate\Support\Collection;

class Dicio implements DictionaryServiceInterface
{
    public function __construct(
        private DicioClientInterface $dicio,
        private ParseDicioResult $parser
    ) {
    }

    /**
     * Searches for a word using Dicio's web pages.
     *
     * @throws DicioException
     */
    public function search(string $word): Collection
    {
        try {
            $response = $this->dicio->search($word);
        } catch (DicioClientException $exception) {
            throw DicioException::clientFailure($exception);
        }

        try {
            return $this->parseResults($response, $word);
        } catch (DicioException $e) {
            return Collection::make();
        }
    }

    /** @throws DicioException */
    private function searchForValidResult(string $word): DOMDocument
    {
        // Explore different word pages to make sure the result pops up.
    }

    private function parseResults(DOMDocument $resultPage, string $word): Collection
    {
        return Collection::make($this->getResultNodes($resultPage, $word))
            ->filter(fn (object $element): bool => $this->isResultStart($element))
            ->map(fn (DOMElement $element): Result => $this->parser->handle($word, $element))
            ->values();
    }

    /** @throws DicioException */
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

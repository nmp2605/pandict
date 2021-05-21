<?php

namespace App\Parsers\Result\Dicio;

use App\Parsers\Result\ParseResult;
use DOMElement;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ParseDicioResult extends ParseResult
{
    private const ETYMOLOGY_BASE_TEXT = 'Etimologia (origem da palavra';

    /** @param DOMElement $result */
    public function parseDetails(object $result): Collection
    {
        $details = Collection::make();

        $this->loopThroughSiblings($result, function (object $element) use (&$details) {
            if ($this->isGrammarGroup($element)) {
                $details->push(['name' => 'classe gramatical', 'value' => $element->textContent]);
            }

            if ($this->isEtymology($element)) {
                $details->push(['name' => 'etimologia', 'value' => $this->clearEtymology($element->textContent)]);
            }
        });

        return $details;
    }

    /** @param DOMElement $result */
    public function parseEntries(object $result): Collection
    {
        $entries = Collection::make();

        $this->loopThroughSiblings($result, function (object $element) use (&$entries) {
            if ($this->isEntry($element)) {
                $entries->push($element->textContent);
            }
        });

        return $entries;
    }

    /** @param DOMElement $result */
    public function parseSourceName(object $result): string
    {
        return 'Dicio';
    }

    /** @param DOMElement $result */
    public function parseSourceUrl(string $word, object $result): string
    {
        return sprintf('%s/%s', config('services.dicio.base_uri'), $word);
    }

    private function loopThroughSiblings(DOMElement $result, callable $callback): void
    {
        $currentElement = $result;
        $shouldLoop = true;

        while ($shouldLoop) {
            $callback($currentElement);

            if ($currentElement->nextSibling === null) {
                break;
            }

            $currentElement = $currentElement->nextSibling;
            $shouldLoop = $this->isOnCurrentResult($currentElement);
        }
    }

    private function isOnCurrentResult(object $element): bool
    {
        return $this->isGrammarGroup($element) === false;
    }

    private function isSpan(object $element): bool
    {
        return $element instanceof DOMElement && $element->nodeName === 'span';
    }

    private function isGrammarGroup(object $element): bool
    {
        return $this->isSpan($element) && $element->getAttribute('class') === 'cl';
    }

    private function isEtymology(object $element): bool
    {
        return $this->isSpan($element) && $element->getAttribute('class') === 'etim';
    }

    private function isEntry(object $element): bool
    {
        return $this->isSpan($element)
            && $element->getAttribute('class') === ''
            && empty($element->textContent) === false;
    }

    private function clearEtymology(string $etymology): string
    {
        if (str_contains($etymology, self::ETYMOLOGY_BASE_TEXT) === false) {
            return $etymology;
        }

        return Str::of($etymology)
            ->after(').')
            ->trim();
    }
}

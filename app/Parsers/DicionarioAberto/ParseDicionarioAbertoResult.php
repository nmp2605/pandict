<?php

namespace App\Parsers\DicionarioAberto;

use App\Exceptions\ParsingException;
use App\Models\Result;
use App\Parsers\ParseResult;
use Illuminate\Support\Collection;
use SimpleXMLElement;

class ParseDicionarioAbertoResult extends ParseResult
{
    private SimpleXMLElement $entry;

    public function __invoke(object $result): Result
    {
        $parsedEntry = simplexml_load_string($result->xml);

        if ($parsedEntry === false) {
            throw ParsingException::dicionarioAbertoFailure();
        }

        $this->entry = $parsedEntry;

        return parent::__invoke($result);
    }

    private function clearString(string $value): string
    {
        return str_replace(['_'], '', $value);
    }

    public function parseDetails(object $result): Collection
    {
        return Collection::make()
            ->when(
                isset($this->entry->sense->gramGrp),
                fn (Collection $results) => $results->push(['name' => 'gênero', 'value' => $this->clearString($this->entry->sense->gramGrp)])
            )
            ->when(
                isset($this->entry->sense->usg),
                fn (Collection $results) => $results->push(['name' => 'uso', 'value' => $this->clearString($this->entry->sense->usg)])
            )
            ->when(
                isset($this->entry->etym),
                fn (Collection $results) => $results->push(['name' => 'etimologia', 'value' => $this->clearString($this->entry->etym)])
            );
    }

    public function parseEntries(object $result): Collection
    {
        if (empty($this->entry->sense->def)) {
            return Collection::make();
        }

        return Collection::make(explode("\n", $this->entry->sense->def))
            ->filter()
            ->map(fn (string $entry) => $this->clearString($entry));
    }

    public function parseSource(object $result): string
    {
        return 'Dicionario Aberto';
    }
}

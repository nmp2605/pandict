<?php

namespace App\Parsers\Result\DicionarioAberto;

use App\Models\Result;
use App\Parsers\Result\ParseResult;
use Illuminate\Support\Collection;
use SimpleXMLElement;

class ParseDicionarioAbertoResult extends ParseResult
{
    private ?SimpleXMLElement $entry;

    public function handle(string $word, object $result): Result
    {
        $this->entry = simplexml_load_string($result->xml) ?: null;

        return parent::handle($word, $result);
    }

    public function parseDetails(object $result): Collection
    {
        if ($this->entry === null) {
            return Collection::make();
        }

        return Collection::make()
            ->when(
                isset($this->entry->sense->gramGrp),
                fn (Collection $results): Collection => $results->push(['name' => 'gênero', 'value' => $this->clearString($this->entry->sense->gramGrp)])
            )
            ->when(
                isset($this->entry->sense->usg),
                fn (Collection $results): Collection => $results->push(['name' => 'uso', 'value' => $this->clearString($this->entry->sense->usg)])
            )
            ->when(
                isset($this->entry->etym),
                fn (Collection $results): Collection => $results->push(['name' => 'etimologia', 'value' => $this->clearString($this->entry->etym)])
            );
    }

    public function parseEntries(object $result): Collection
    {
        if ($this->entry === null || empty($this->entry->sense->def)) {
            return Collection::make();
        }

        return Collection::make(explode("\n", $this->entry->sense->def))
            ->filter()
            ->map(fn (string $def): string => $this->clearString($def));
    }

    public function parseSourceName(object $result): string
    {
        return 'Dicionario Aberto';
    }

    public function parseSourceUrl(string $word, object $result): string
    {
        return sprintf('%s/word/%s', config('services.dicionario_aberto.base_uri'), $word);
    }

    private function clearString(string $value): string
    {
        return str_replace(['_'], '', $value);
    }
}

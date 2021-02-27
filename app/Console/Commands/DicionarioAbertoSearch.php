<?php

namespace App\Console\Commands;

use App\Actions\Searches\DicionarioAbertoSearch as SearchAction;
use App\Models\Result;
use Illuminate\Console\Command;
use RuntimeException;

class DicionarioAbertoSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dicionario-aberto:search {word}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches for a word using the API from DicionarioAberto.';

    private SearchAction $search;

    /** Create a new command instance. */
    public function __construct(SearchAction $search)
    {
        parent::__construct();

        $this->search = $search;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $word = $this->argument('word');

        if (is_string($word) === false) {
            throw new RuntimeException('The word is not a string.');
        }

        $results = ($this->search)($word);

        $results->each(function (Result $result): void {
            foreach ($result->details as $detail) {
                $this->line(sprintf('%s: %s', $detail['name'], $detail['value']));
            }

            if ($result->details->isNotEmpty()) {
                $this->line("\n");
            }

            foreach ($result->entries as $entry) {
                $this->line(sprintf('- %s', $entry));
            }

            $this->line("\n");
        });

        return 0;
    }
}

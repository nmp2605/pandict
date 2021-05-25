<?php

namespace App\Console\Commands;

use App\Services\DicionarioAberto\DicionarioAberto;
use App\Services\DicionarioAberto\DicionarioAbertoException;
use Illuminate\Console\Command;

class DicionarioAbertoSearch extends Command
{
    /** @var string */
    protected $signature = 'dicionario-aberto:search {word}';

    /** @var string */
    protected $description = 'Searches for a word using DicionarioAberto.';

    private DicionarioAberto $dicionarioAberto;

    public function __construct(DicionarioAberto $dicionarioAberto)
    {
        parent::__construct();

        $this->dicionarioAberto = $dicionarioAberto;
    }

    public function handle(): int
    {
        /** @var string $word */
        $word = $this->argument('word');

        try {
            $results = $this->dicionarioAberto->search($word);
        } catch (DicionarioAbertoException $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        $this->info($results->toJson());

        return Command::SUCCESS;
    }
}

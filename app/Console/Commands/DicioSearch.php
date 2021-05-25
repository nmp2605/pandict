<?php

namespace App\Console\Commands;

use App\Services\Dicio\Dicio;
use App\Services\Dicio\DicioException;
use Illuminate\Console\Command;

class DicioSearch extends Command
{
    /** @var string */
    protected $signature = 'dicio:search {word}';

    /** @var string */
    protected $description = 'Searches for a word using Dicio.';

    private Dicio $dicio;

    /** Create a new command instance. */
    public function __construct(Dicio $dicio)
    {
        parent::__construct();

        $this->dicio = $dicio;
    }

    public function handle(): int
    {
        /** @var string $word */
        $word = $this->argument('word');

        try {
            $results = $this->dicio->search($word);
        } catch (DicioException $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        $this->info($results->toJson());

        return Command::SUCCESS;
    }
}

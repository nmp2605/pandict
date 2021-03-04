<?php

namespace App\Http\Clients\DicionarioAberto;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Support\Collection;

class MockDicionarioAbertoClient implements DicionarioAbertoClientInterface
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function search(string $word): array
    {
        return Collection::make([0, 1, 2])
            ->map(fn (): object => $this->generateResultForWord($word))
            ->toArray();
    }

    private function generateResultForWord(string $word): object
    {
        return (object) [
            'word' => $word,
            'deletor' => null,
            'revision_id' => 2,
            'moderator' => null,
            'xml' => <<<XML
            <entry n="1" id="nona:1" type="hom">
                <form>
                    <orth>{$word}</orth>
                </form>
                <sense>
                    <gramGrp>{$this->faker->randomElement(['f.', 'v.', 's.'])}</gramGrp>
                    <usg type="style">{$this->faker->randomElement(['Ant.', 'Nov.'])}</usg>
                    <def>{$this->faker->sentence}</def>
                </sense>
                <etym orig="Lat">(Lat. _{$this->faker->word}_)</etym>
            </entry>
            XML,
            'deleted' => 0,
            'last_revision' => 2,
            'sense' => 1,
            'creator' => "ambs",
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'normalized' => $word,
            'derived_from' => null,
            'word_id' => $this->faker->randomNumber(5),
        ];
    }
}

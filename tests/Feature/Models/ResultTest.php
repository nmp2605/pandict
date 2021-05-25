<?php

namespace Tests\Feature\Models;

use App\Models\Result;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_fill_the_right_attributres(): void
    {
        $result = Result::make([
            'details' => Collection::make(),
            'entries' => Collection::make(),
            'source_name' => 'Source',
            'source_url' => 'http://source.test',
        ]);

        $this->assertEquals(Collection::make(), $result->details);
        $this->assertEquals(Collection::make(), $result->entries);
        $this->assertEquals('Source', $result->source_name);
        $this->assertEquals('http://source.test', $result->source_url);
    }

    /** @test */
    public function it_should_cast_to_the_right_types(): void
    {
        $result = Result::make([
            'details' => Collection::make([]),
            'entries' => Collection::make([]),
        ])->refresh();

        $this->assertInstanceOf(Collection::class, $result->details);
        $this->assertInstanceOf(Collection::class, $result->entries);
    }

    /** @test */
    public function it_should_set_the_details_attribute_to_the_right_format(): void
    {
        $result = Result::make([
            'details' => Collection::make([
                ['name' => 'Upper', 'value' => 'This is a value'],
            ]),
        ]);

        $this->assertEquals('upper', $result->details[0]['name']);
        $this->assertEquals('This is a value', $result->details[0]['value']);
    }

    /** @test */
    public function it_should_belong_to_a_word(): void
    {
        $word = Word::create(['value' => 'word']);
        $result = Result::make([
            'details' => Collection::make(),
            'entries' => Collection::make(),
            'source_name' => 'Source',
            'source_url' => 'http://source.test',
        ]);

        $word->results()->save($result);

        $this->assertDatabaseHas('results', [
            'id' => $result->refresh()->id,
            'word_id' => $word->id,
        ]);
    }
}

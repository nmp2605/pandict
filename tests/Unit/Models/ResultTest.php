<?php

namespace Tests\Unit\Models;

use App\Models\Result;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /** @test */
    public function it_should_set_the_details_attribute_with_lowercase_name(): void
    {
        $result = new Result;

        $result->fill([
            'details' => Collection::make([
                ['name' => 'Upper', 'value' => 'This is a value'],
            ]),
        ]);

        $this->assertEquals('upper', $result->details[0]['name']);
        $this->assertEquals('This is a value', $result->details[0]['value']);
    }
}

<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ViewControllerTest extends TestCase
{
    private function getView(): TestResponse
    {
        return $this->getJson(route('view'));
    }

    /** @test */
    public function it_should_get_the_main_view(): void
    {
        $this->getView()
            ->assertOk()
            ->assertViewIs('main');
    }
}

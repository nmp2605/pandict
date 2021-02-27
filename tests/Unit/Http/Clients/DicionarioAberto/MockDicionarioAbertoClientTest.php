<?php

namespace Tests\Unit\Http\Clients\DicionarioAberto;

use App\Http\Clients\DicionarioAberto\MockDicionarioAbertoClient;
use Carbon\Carbon;
use RuntimeException;
use Tests\TestCase;

class MockDicionarioAbertoClientTest extends TestCase
{
    private MockDicionarioAbertoClient $dicionarioAberto;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2000-01-01 00:00:00');

        $this->dicionarioAberto = new MockDicionarioAbertoClient($this->app->make(\Faker\Generator::class));
    }

    /** @test */
    public function it_should_get_a_mock_response_from_dicionario_aberto(): void
    {
        $response = $this->dicionarioAberto->search('word');

        $this->assertIsArray($response);
        $this->assertIsObject($response[0]);

        $this->assertEquals('word', $response[0]->word);
        $this->assertNull($response[0]->deletor);
        $this->assertEquals(2, $response[0]->revision_id);
        $this->assertNull($response[0]->moderator);

        $xml = simplexml_load_string($response[0]->xml);

        if ($xml === false) {
            throw new RuntimeException('The XML is invalid.');
        }

        $this->assertEquals('word', (string) $xml->form->orth);
        $this->assertObjectHasAttribute('gramGrp', $xml->sense);
        $this->assertObjectHasAttribute('usg', $xml->sense);
        $this->assertObjectHasAttribute('def', $xml->sense);
        $this->assertObjectHasAttribute('etym', $xml);

        $this->assertEquals(0, $response[0]->deleted);
        $this->assertEquals(2, $response[0]->last_revision);
        $this->assertEquals(1, $response[0]->sense);
        $this->assertEquals('ambs', $response[0]->creator);
        $this->assertEquals(Carbon::now()->format('Y-m-d H:i:s'), $response[0]->timestamp);
        $this->assertEquals('word', $response[0]->normalized);
        $this->assertNull($response[0]->derived_from);
        $this->assertIsNumeric($response[0]->word_id);
    }
}

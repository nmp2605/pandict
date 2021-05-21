<?php

namespace Tests\Feature\Parsers\Result\Dicio;

use App\Parsers\Result\Dicio\ParseDicioResult;
use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;
use Tests\TestCase;

class ParseDicioResultTest extends TestCase
{
    private ParseDicioResult $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = new ParseDicioResult;
    }

    private function getFirstElement(string $html): DOMElement
    {
        $document = new DOMDocument;

        $document->loadHTML($html);

        $xpath = new DOMXPath($document);
        $resultNodes = $xpath->query("//*[@class='cl']");

        if ($resultNodes === false) {
            throw new RuntimeException('The HTML is invalid.');
        }

        $resultNode = $resultNodes->item(0);

        if ($resultNode === null || ! $resultNode instanceof DOMElement) {
            throw new RuntimeException('The HTML is invalid.');
        }

        return $resultNode;
    }

    /** @test */
    public function it_should_parse_the_details_with_cl(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('classe gramatical', $result->details[0]['name']);
        $this->assertEquals('Class', $result->details[0]['value']);
    }

    /** @test */
    public function it_should_parse_the_details_with_cl_and_etymology(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        <br/>
        <span class="etim">Etymology</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('classe gramatical', $result->details[0]['name']);
        $this->assertEquals('Class', $result->details[0]['value']);
        $this->assertEquals('etimologia', $result->details[1]['name']);
        $this->assertEquals('Etymology', $result->details[1]['value']);
    }

    /** @test */
    public function it_should_parse_the_details_with_cl_and_a_clean_etymology(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        <br/>
        <span class="etim">Etimologia (origem da palavra word). Etymology</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('classe gramatical', $result->details[0]['name']);
        $this->assertEquals('Class', $result->details[0]['value']);
        $this->assertEquals('etimologia', $result->details[1]['name']);
        $this->assertEquals('Etymology', $result->details[1]['value']);
    }

    /** @test */
    public function it_should_parse_one_entry(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        <span>This is an entry</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('This is an entry', $result->entries[0]);
    }

    /** @test */
    public function it_should_parse_two_entries(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        <span>This is an entry</span>
        <span>This is another one</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('This is an entry', $result->entries[0]);
        $this->assertEquals('This is another one', $result->entries[1]);
    }

    /** @test */
    public function it_should_parse_no_entries(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertCount(0, $result->entries);
    }

    /** @test */
    public function it_should_parse_the_source_name(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals('Dicio', $result->source_name);
    }

    /** @test */
    public function it_should_parse_the_source_url(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertEquals(sprintf('%s/%s', config('services.dicio.base_uri'), 'word'), $result->source_url);
    }

    /** @test */
    public function it_should_always_stop_before_the_next_cl(): void
    {
        $element = $this->getFirstElement(<<<HTML
        <span class="cl">Class</span>
        <span>This is an entry</span>
        <br/>
        <span class="cl">Other</span>
        <span>This is a completely different result</span>
        HTML);

        $result = $this->parser->handle('word', $element);

        $this->assertCount(1, $result->details);
        $this->assertCount(1, $result->entries);
    }
}

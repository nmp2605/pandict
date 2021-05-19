<?php

namespace Tests\Feature\Parsers\DicionarioAberto;

use App\Models\Result;
use App\Parsers\DicionarioAberto\ParseDicionarioAbertoResult;
use Tests\TestCase;

class ParseDicionarioAbertoResultTest extends TestCase
{
    private ParseDicionarioAbertoResult $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = new ParseDicionarioAbertoResult;
    }

    private function parseResultWithXml(string $xml): Result
    {
        return $this->parser->handle((object) [
            'xml' => $xml,
        ]);
    }

    /** @test */
    public function it_should_parse_the_details_with_gramGrp(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry>
            <sense>
                <gramGrp>f.</gramGrp>
            </sense>
        </entry>
        XML);

        $this->assertEquals('gênero', $result->details[0]['name']);
        $this->assertEquals('f.', $result->details[0]['value']);
    }

    /** @test */
    public function it_should_parse_the_details_with_usg(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry>
            <sense>
                <usg type="style">Ant.</usg>
            </sense>
        </entry>
        XML);

        $this->assertEquals('uso', $result->details[0]['name']);
        $this->assertEquals('Ant.', $result->details[0]['value']);
    }

    /** @test */
    public function it_should_parse_the_details_with_etym(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry>
            <etym>(Lat. _word_)</etym>
        </entry>
        XML);

        $this->assertEquals('etimologia', $result->details[0]['name']);
        $this->assertEquals('(Lat. word)', $result->details[0]['value']);
    }

    /** @test */
    public function it_should_parse_with_no_details(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry></entry>
        XML);

        $this->assertEmpty($result->details);
    }

    /** @test */
    public function it_should_parse_one_entry(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry>
            <sense>
                <def>Full _definition_</def>
            </sense>
        </entry>
        XML);

        $this->assertEquals('Full definition', $result->entries[0]);
    }

    /** @test */
    public function it_should_parse_more_than_one_entry(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry>
            <sense>
                <def>First _definition_\nSecond _definition_\nThird _definition_</def>
            </sense>
        </entry>
        XML);

        $this->assertEquals('First definition', $result->entries[0]);
        $this->assertEquals('Second definition', $result->entries[1]);
        $this->assertEquals('Third definition', $result->entries[2]);
    }

    /** @test */
    public function it_should_parse_with_no_entries(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry></entry>
        XML);

        $this->assertEmpty($result->entries);
    }

    /** @test */
    public function it_should_parse_the_source(): void
    {
        $result = $this->parseResultWithXml(<<<XML
        <entry></entry>
        XML);

        $this->assertEquals('Dicionario Aberto', $result->source);
    }
}

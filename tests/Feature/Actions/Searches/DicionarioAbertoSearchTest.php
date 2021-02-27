<?php

namespace Tests\Feature\Actions\Searches;

use App\Actions\Searches\DicionarioAbertoSearch;
use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Models\Result;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class DicionarioAbertoSearchTest extends TestCase
{
    /** @var DicionarioAbertoClientInterface&MockInterface */
    private $dicionarioAberto;
    private DicionarioAbertoSearch $search;

    public function setUp(): void
    {
        parent::setUp();

        $this->dicionarioAberto = $this->mock(DicionarioAbertoClientInterface::class);
        $this->search = $this->app->make(DicionarioAbertoSearch::class);
    }

    /** @test */
    public function it_should_fetch_and_parse_a_single_result(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn([
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <gramGrp>f.</gramGrp>
                            <usg type="style">Ant.</usg>
                            <def>This is an entry\nThis is another one</def>
                        </sense>
                        <etym orig="Lat">(Lat. _word_)</etym>
                    </entry>
                    XML,
                ],
            ]);

        $results = ($this->search)('word');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf(Result::class, $results[0]);

        $this->assertEquals(['name' => 'gênero', 'value' => 'f.'], $results[0]->details[0]);
        $this->assertEquals(['name' => 'uso', 'value' => 'Ant.'], $results[0]->details[1]);
        $this->assertEquals(['name' => 'etimologia', 'value' => '(Lat. word)'], $results[0]->details[2]);
        $this->assertEquals('This is an entry', $results[0]->entries[0]);
        $this->assertEquals('This is another one', $results[0]->entries[1]);
    }

    /** @test */
    public function it_should_fetch_and_parse_more_than_one_result(): void
    {
        $this->dicionarioAberto->shouldReceive('search')
            ->with('word')
            ->once()
            ->andReturn([
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <gramGrp>f.</gramGrp>
                            <usg type="style">Ant.</usg>
                            <def>This is an entry\nThis is another one</def>
                        </sense>
                        <etym orig="Lat">(Lat. _word_)</etym>
                    </entry>
                    XML,
                ],
                (object) [
                    'xml' => <<<XML
                    <entry n="1" id="nona:1" type="hom">
                        <sense>
                            <gramGrp>m.</gramGrp>
                            <usg type="style">Nov.</usg>
                            <def>Other entry\nOne more</def>
                        </sense>
                        <etym orig="Gr">(Gr. _word_)</etym>
                    </entry>
                    XML,
                ],
            ]);

        $results = ($this->search)('word');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf(Result::class, $results[0]);

        $this->assertEquals(['name' => 'gênero', 'value' => 'f.'], $results[0]->details[0]);
        $this->assertEquals(['name' => 'uso', 'value' => 'Ant.'], $results[0]->details[1]);
        $this->assertEquals(['name' => 'etimologia', 'value' => '(Lat. word)'], $results[0]->details[2]);
        $this->assertEquals('This is an entry', $results[0]->entries[0]);
        $this->assertEquals('This is another one', $results[0]->entries[1]);

        $this->assertInstanceOf(Result::class, $results[1]);

        $this->assertEquals(['name' => 'gênero', 'value' => 'm.'], $results[1]->details[0]);
        $this->assertEquals(['name' => 'uso', 'value' => 'Nov.'], $results[1]->details[1]);
        $this->assertEquals(['name' => 'etimologia', 'value' => '(Gr. word)'], $results[1]->details[2]);
        $this->assertEquals('Other entry', $results[1]->entries[0]);
        $this->assertEquals('One more', $results[1]->entries[1]);
    }
}

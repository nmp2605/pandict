<?php

namespace Tests\Unit\Http\Clients\Dicio;

use App\Http\Clients\Dicio\MockDicioClient;
use Carbon\Carbon;
use DOMElement;
use DOMText;
use DOMXPath;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class MockDicioClientTest extends TestCase
{
    private MockDicioClient $dicio;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2000-01-01 00:00:00');

        $this->dicio = new MockDicioClient(\Faker\Factory::create());
    }

    /** @test */
    public function it_should_get_a_mock_response_from_dicio(): void
    {
        $response = $this->dicio->search('word');

        $xpath = new DOMXPath($response);
        $resultNodes = $xpath->query("//*[@class='significado textonovo']");

        if ($resultNodes === false) {
            throw new RuntimeException('The DOMNodeList is invalid.');
        }

        $this->assertCount(1, $resultNodes);

        $resultNode = $resultNodes->item(0);

        if ($resultNode === null) {
            throw new RuntimeException('The DOMNodeList is invalid.');
        }

        $this->assertInstanceOf(DOMElement::class, $resultNode);

        $childNodes = Collection::make($resultNode->childNodes);

        $this->assertInstanceOf(DOMElement::class, $childNodes[0]);
        $this->assertEquals('span', $childNodes[0]->nodeName);
        $this->assertEquals('cl', $childNodes[0]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[1]);
        $this->assertEquals("\n", $childNodes[1]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[2]);
        $this->assertEquals('span', $childNodes[2]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[3]);
        $this->assertEquals("\n", $childNodes[3]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[4]);
        $this->assertEquals('span', $childNodes[4]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[5]);
        $this->assertEquals("\n", $childNodes[5]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[6]);
        $this->assertEquals('br', $childNodes[6]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[7]);
        $this->assertEquals("\n", $childNodes[7]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[8]);
        $this->assertEquals('span', $childNodes[8]->nodeName);
        $this->assertEquals('etim', $childNodes[8]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[9]);
        $this->assertEquals("\n", $childNodes[9]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[10]);
        $this->assertEquals('br', $childNodes[10]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[11]);
        $this->assertEquals("\n", $childNodes[11]->wholeText);

        $this->assertInstanceOf(DOMElement::class, $childNodes[12]);
        $this->assertEquals('span', $childNodes[12]->nodeName);
        $this->assertEquals('cl', $childNodes[12]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[13]);
        $this->assertEquals("\n", $childNodes[13]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[14]);
        $this->assertEquals('span', $childNodes[14]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[15]);
        $this->assertEquals("\n", $childNodes[15]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[16]);
        $this->assertEquals('span', $childNodes[16]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[17]);
        $this->assertEquals("\n", $childNodes[17]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[18]);
        $this->assertEquals('br', $childNodes[18]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[19]);
        $this->assertEquals("\n", $childNodes[19]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[20]);
        $this->assertEquals('span', $childNodes[20]->nodeName);
        $this->assertEquals('etim', $childNodes[20]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[21]);
        $this->assertEquals("\n", $childNodes[21]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[22]);
        $this->assertEquals('br', $childNodes[22]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[23]);
        $this->assertEquals("\n", $childNodes[23]->wholeText);

        $this->assertInstanceOf(DOMElement::class, $childNodes[24]);
        $this->assertEquals('span', $childNodes[24]->nodeName);
        $this->assertEquals('cl', $childNodes[24]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[25]);
        $this->assertEquals("\n", $childNodes[25]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[26]);
        $this->assertEquals('span', $childNodes[26]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[27]);
        $this->assertEquals("\n", $childNodes[27]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[28]);
        $this->assertEquals('span', $childNodes[28]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[29]);
        $this->assertEquals("\n", $childNodes[29]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[30]);
        $this->assertEquals('br', $childNodes[30]->nodeName);
        $this->assertInstanceOf(DOMText::class, $childNodes[31]);
        $this->assertEquals("\n", $childNodes[31]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[32]);
        $this->assertEquals('span', $childNodes[32]->nodeName);
        $this->assertEquals('etim', $childNodes[32]->getAttribute('class'));
        $this->assertInstanceOf(DOMText::class, $childNodes[33]);
        $this->assertEquals("\n", $childNodes[33]->wholeText);
        $this->assertInstanceOf(DOMElement::class, $childNodes[34]);
        $this->assertEquals('br', $childNodes[34]->nodeName);
    }
}

<?php

use Deblan\Csv\Csv;

class CsvTest extends \PHPUnit_Framework_TestCase
{
    public function testAddLine()
    {
        $csv = new Csv();
        $csv->addLine(array('foo', 'bar'));
        $this->assertEquals('"foo";"bar"'."\n", $csv->compile());
    }

    public function testSetLegend()
    {
        $csv = new Csv();

        $this->assertEquals(false, $csv->getHasLegend());

        $csv->setLegend(array('bim', 'bam'));
        $csv->addLine(array('foo', 'bar'));

        $this->assertEquals(true, $csv->getHasLegend());
        $this->assertEquals(
            '"bim";"bam"'."\n".
            '"foo";"bar"'."\n",
            $csv->compile()
        );

        $csv = new Csv();

        $csv->addLine(array('foo', 'bar'));
        $csv->setLegend(array('bim', 'bam'));

        $this->assertEquals(true, $csv->getHasLegend());
        $this->assertEquals(
            '"bim";"bam"'."\n".
            '"foo";"bar"'."\n",
            $csv->compile()
        );
    }

    public function testHasDatas()
    {
        $csv = new Csv();
        $this->assertEquals(false, $csv->hasDatas());

        $csv->setLegend(array('bim', 'bam'));
        $this->assertEquals(false, $csv->hasDatas());

        $csv->addLine(array('foo', 'bar'));
        $this->assertEquals(true, $csv->hasDatas());

        $csv = new Csv();
        $csv->addLine(array('foo', 'bar'));
        $this->assertEquals(true, $csv->hasDatas());
    }

    public function testDatasToCsvLine()
    {
        $csv = new Csv();
        $csv->addLine(array('fo\\o', 'bar'));
        $this->assertEquals('"fo\\\\o";"bar"'."\n", $csv->compile());

        $csv = new Csv();
        $csv->setDelimiter(':');
        $csv->addLine(array('foo', 'bar'));
        $this->assertEquals('"foo":"bar"'."\n", $csv->compile());

        $csv = new Csv();
        $csv->setDelimiter(':');
        $csv->addLine(array('fo:o', 'bar'));
        $this->assertEquals('"fo:o":"bar"'."\n", $csv->compile());

        $csv = new Csv();
        $csv->setDelimiter(':');
        $csv->setEnclosure('');
        $csv->addLine(array('fo:o', 'bar'));
        $this->assertEquals('fo\\:o:bar'."\n", $csv->compile());

        $csv = new Csv();
        $csv->setEnclosure('#');
        $csv->addLine(array('foo', 'bar'));
        $this->assertEquals('#foo#;#bar#'."\n", $csv->compile());

        $csv = new Csv();
        $csv->setEnclosure('#');
        $csv->addLine(array('f#oo', 'bar'));
        $this->assertEquals('#f\\#oo#;#bar#'."\n", $csv->compile());
    }
}

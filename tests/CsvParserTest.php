<?php

use Deblan\Csv\CsvParser;

class CsvParserParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGettersAndSettersAndDefaultValues()
    {
        $parser = new CsvParser();
        $this->assertEquals(';', $parser->getDelimiter());
        $parser->setDelimiter('#');
        $this->assertEquals('#', $parser->getDelimiter());

        $parser = new CsvParser();
        $this->assertEquals("\n", $parser->getEndOfLine());
        $parser->setEndOfLine("\r\n");
        $this->assertEquals("\r\n", $parser->getEndOfLine());

        $parser = new CsvParser();
        $this->assertEquals('"', $parser->getEnclosure());
        $parser->setEnclosure("'");
        $this->assertEquals("'", $parser->getEnclosure());

        $parser = new CsvParser();
        $this->assertEquals([], $parser->getDatas());
        $this->assertEquals([], $parser->getHeaders());
        $this->assertEquals(false, $parser->getHasHeaders());
        $parser->setHasHeaders(true);
        $this->assertEquals(true, $parser->getHasHeaders());
    }

    public function testParser()
    {
        $parser = new CsvParser();
        $this->assertEquals(['foo', 'bar'], $parser->parseLine('"foo";"bar"'));
        $this->assertEquals([], $parser->getDatas());
        $this->assertEquals([], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->parseString('"foo";"bar"');
        $this->assertEquals([['foo', 'bar']], $parser->getDatas());
        $this->assertEquals([], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->parseString('"foo";"bar"'."\n".'"foo2";"bar2"');
        $this->assertEquals([['foo', 'bar'], ['foo2', 'bar2']], $parser->getDatas());
        $this->assertEquals([], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->parseString('"foo";"bar"'."\n".'"foo2";"bar2"');
        $this->assertEquals([['foo2', 'bar2', 'foo' => 'foo2', 'bar' => 'bar2']], $parser->getDatas());
        $this->assertEquals(['foo', 'bar'], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->setEnclosure(null);
        $parser->parseString('foo;bar'."\n".'foo2;bar2;boo2');
        $this->assertEquals([['foo2', 'bar2', 'boo2', 'foo' => 'foo2', 'bar' => 'bar2']], $parser->getDatas());
        $this->assertEquals(['foo', 'bar'], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->parseString('foo;bar'."\n".'foo2');
        $this->assertEquals([['foo2', 'foo' => 'foo2', 'bar' => null]], $parser->getDatas());
        $this->assertEquals(['foo', 'bar'], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->parseFile(__DIR__.'/fixtures/example.csv');
        $this->assertEquals(
            [
                [
                    'foo 1',
                    'bar 1',
                    'FOO' => 'foo 1',
                    'BAR' => 'bar 1',
                ],
                [
                    'foo 2',
                    'bar 2',
                    'FOO' => 'foo 2',
                    'BAR' => 'bar 2',
                ],
                [
                    'foo 3',
                    'bar 3',
                    'FOO' => 'foo 3',
                    'BAR' => 'bar 3',
                ],
            ],
            $parser->getDatas()
        );
        $this->assertEquals(['FOO', 'BAR'], $parser->getHeaders());

        $parser = new CsvParser();
        $parser->setHasHeaders(false);
        $parser->parseFile(__DIR__.'/fixtures/example2.csv');
        $this->assertEquals(
            [
                [
                    'foo 1',
                    'bar 1',
                ],
                [
                    'foo 2',
                    'ba"r 2',
                ],
                [
                    'foo 3',
                    'bar 3',
                ],
            ],
            $parser->getDatas()
        );

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->setEnclosure("'");
        $parser->setDelimiter('#');
        $parser->parseFile(__DIR__.'/fixtures/example3.csv');
        $this->assertEquals(
            [
                [
                    'foo 1',
                    '',
                    "FO'O" => 'foo 1',
                    'BAR' => '',
                ],
                [
                    'foo 1b',
                    "FO'O" => 'foo 1b',
                    'BAR' => null,
                ],
                [
                    'foo 2',
                    'bar 2',
                    'unexpected 3',
                    "FO'O" => 'foo 2',
                    'BAR' => 'bar 2',
                ],
                [
                    'foo 3',
                    'bar 3',
                    "FO'O" => 'foo 3',
                    'BAR' => 'bar 3',
                ],
            ],
            $parser->getDatas()
        );

        $parser = new CsvParser();
        $parser->setHasHeaders(true);
        $parser->setEnclosure("'");
        $parser->setDelimiter('#');
        $parser->setEndOfLine("\r\n");
        $parser->parseFile(__DIR__.'/fixtures/example4.csv');
        $this->assertEquals(
            [
                [
                    'foo 1',
                    '',
                    "FO'O" => 'foo 1',
                    'BAR' => '',
                ],
                [
                    'foo 1b',
                    "FO'O" => 'foo 1b',
                    'BAR' => null,
                ],
                [
                    'foo 2',
                    'bar 2',
                    'unexpected 3',
                    "FO'O" => 'foo 2',
                    'BAR' => 'bar 2',
                ],
                [
                    'foo 3',
                    'bar 3',
                    "FO'O" => 'foo 3',
                    'BAR' => 'bar 3',
                ],
            ],
            $parser->getDatas()
        );
    }
}

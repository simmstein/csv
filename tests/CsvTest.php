<?php

use Deblan\Csv\Csv;
use PHPUnit_Framework_TestCase;

/**
 * class CsvTest.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class CsvTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSettersAndDefaultValues()
    {
        $csv = new Csv();
        $this->assertEquals(';', $csv->getDelimiter());
        $csv->setDelimiter('#');
        $this->assertEquals('#', $csv->getDelimiter());

        $csv = new Csv();
        $this->assertEquals("\n", $csv->getEndOfLine());
        $csv->setEndOfLine("\r\n");
        $this->assertEquals("\r\n", $csv->getEndOfLine());

        $csv = new Csv();
        $this->assertEquals([], $csv->getDatas());
        $csv->setDatas([['a', 'b', 'c'], ['d', 'e', 'f']]);
        $this->assertEquals([['a', 'b', 'c'], ['d', 'e', 'f']], $csv->getDatas());

        $csv = new Csv();
        $this->assertEquals([], $csv->getHeaders());
        $csv->setHeaders(['a', 'b', 'c']);
        $this->assertEquals(['a', 'b', 'c'], $csv->getHeaders());

        $csv = new Csv();
        $this->assertEquals('UTF-8', $csv->getCharset());
        $csv->setCharset('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $csv->getCharset());
    }

    public function testRender()
    {
        $csv = new Csv();
        $this->assertEquals(null, $csv->render());
        $csv->addData(['foo', 'bar']);
        $this->assertEquals('"foo";"bar"', $csv->render());

        $csv = new Csv();
        $csv->appendData(['foo', 'bar']);
        $csv->appendData(['foo2', 'bar2']);
        $this->assertEquals('"foo";"bar"'."\n".'"foo2";"bar2"', $csv->render());

        $csv = new Csv();
        $csv->preprendData(['foo2', 'bar2']);
        $csv->preprendData(['foo', 'bar']);
        $this->assertEquals('"foo";"bar"'."\n".'"foo2";"bar2"', $csv->render());

        $csv = new Csv();
        $csv->addData(['foo', 'bar']);
        $csv->preprendData(['foo2', 'bar2']);
        $csv->appendData(['foo3', 'bar3']);
        $this->assertEquals('"foo2";"bar2"'."\n".'"foo";"bar"'."\n".'"foo3";"bar3"', $csv->render());

        $csv = new Csv();
        $csv->setHeaders(['a', 'b']);
        $csv->addData(['foo', 'bar']);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', $csv->render());

        $csv = new Csv();
        $csv->setHeaders(['a"b', 'cd"']);
        $csv->addData(['f"oo', 'b""ar']);
        $this->assertEquals('"a""b";"cd"""'."\n".'"f""oo";"b""""ar"', $csv->render());

        $csv = new Csv();
        $csv->addData(['foo', 'bar']);
        $csv->setHeaders(['a', 'b']);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', $csv->render());

        $csv = new Csv();
        $csv->addData(['foo', 'bar']);
        $csv->addData(['foo2', 'bar2']);
        $csv->setHeaders(['a', 'b']);
        $csv->setEndOfLine("\r\n");
        $this->assertEquals('"a";"b"'."\r\n".'"foo";"bar"'."\r\n".'"foo2";"bar2"', $csv->render());

        $csv = new Csv();
        $csv->setHeaders(['a', "b'd"]);
        $csv->addData(["fo'o", 'bar']);
        $csv->setEnclosure("'");
        $this->assertEquals("'a';'b''d'"."\n"."'fo''o';'bar'", $csv->render());

        $csv = new Csv();
        $csv->setHeaders(['a', 'b']);
        $csv->addData(['foo', 'bar']);
        $csv->setDelimiter('#');
        $this->assertEquals('"a"#"b"'."\n".'"foo"#"bar"', $csv->render());
        
        $filename = tempnam(sys_get_temp_dir(), 'csvtests');

        $csv = new Csv();
        $csv->setHeaders(['a', 'b']);
        $csv->addData(['foo', 'bar']);
        $render = $csv->render($filename);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', $render);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', file_get_contents($filename));
        $render = $csv->render($filename);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', $render);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"', file_get_contents($filename));

        unlink($filename);

        $csv = new Csv();
        $csv->setHeaders(['a', 'b']);
        $csv->addData(['foo', 'bar']);
        $csv->render($filename);

        $csv = new Csv();
        $csv->addData(['foo2', 'bar2']);
        $render = $csv->render($filename, FILE_APPEND);
        $this->assertEquals('"a";"b"'."\n".'"foo";"bar"'."\n".'"foo2";"bar2"', file_get_contents($filename));
        
        unlink($filename);
    }

    public function testEncoding()
    {
        $csv = new Csv();
        $csv->addData(['é']);
        $render = $csv->render();
        $this->assertEquals('"é"', $csv->render());

        $csv = new Csv();
        $csv->addData(['é']);
        $csv->setCharset('ISO-8859-1');
        $render = $csv->render();
        $this->assertEquals('"é"', utf8_encode($csv->render()));
    }
}

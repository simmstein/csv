CSV parser/generator
====================

A simple PHP library to parse and generate CSV files.

## Examples

### Generator

```php
use Deblan\Csv\Csv;
use Deblan\Csv\Exception\CsvInvalidParameterException;

try {
    $csv = new Csv();

    $csv->addLine(array('Foo', '$1000'));
    $csv->addLine(array('Bar', '$600'));

    $result = $csv->compile();
} catch(CsvInvalidParameterException $e) {

}
```

```php
use Deblan\Csv\Csv;
use Deblan\Csv\Exception\CsvInvalidParameterException;

try {
    $csv = new Csv();

    $csv->setLegend(array('product name', 'price'));
    $csv->addLine(array('Foo', '$1000'));
    $csv->addLine(array('Bar', '$600'));

    $csv->compileToFile('products.csv');
} catch(CsvInvalidParameterException $e) {

}
```

### Parser

```php
use Deblan\Csv\Exception\CsvParserInvalidParameterException;
use Deblan\Csv\Exception\CsvParserException;

try {
    $csv = new CsvParser('products.csv');
	$csv->setHasLegend(true);
	$csv->parse();

	$legend = $csv->getLegend();
	$products = $csv->getDatas();
} catch(CsvParserException $e) {

} catch(CsvParserInvalidParameterException $e) {

}
```

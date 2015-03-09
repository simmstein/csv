CSV parser/generator
====================

A simple PHP library to parse and generate CSV files.

## Examples

### Generator

```php
use Deblan\Csv\Csv;

$csv = new Csv();

$csv->addLine(array('Foo', '$1000'));
$csv->addLine(array('Bar', '$600'));

$result = $csv->compile();
```

```php
use Deblan\Csv\Csv;

$csv = new Csv();

$csv->setLegend(array('product name', 'price'));
$csv->addLine(array('Foo', '$1000'));
$csv->addLine(array('Bar', '$600'));

$csv->compileToFile('products.csv');
```

### Parser

```php
use Deblan\Csv\CsvParser;

$csv = new CsvParser('products.csv');
$csv->setHasLegend(true);
$csv->parse();

$legend = $csv->getLegend();
$products = $csv->getDatas();
```

CSV parser/generator
====================

A simple PHP library to parse and generate CSV files.

## Composer installation

```
$ composer require deblan/csv
```

Or in your composer.json:

```
{
    "require": {
        [...]
        "deblan/csv": "dev-master"
    }
}
```

## Usages

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

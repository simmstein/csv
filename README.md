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
        "deblan/csv": "~2"
    }
}
```

## Usages

### Generator

```php
use Deblan\Csv\Csv;

$csv = new Csv();

// Defines the delimiter (default is ;)
$csv->setDelimiter(";");

// Defines the enclosure (default is ")
$csv->setEnclosure('"');

// Defines the end of line (default is \n)
$csv->setEndOfLine("\n");

// Defines the charset (default is UTF-8)
$csv->setCharset("UTF-8");

// Add a new line at the end
$csv->addData(['Foo', '$1000'));

// Add a new line at the end
$csv->appendData(['Bar', '$600']);

// Add a new line at the beginning
$csv->prependData(['Boo', '$3000']);

// Defines all the datas
$csv->setDatas([[...], [...]]);

// Defines the header
$csv->setHeaders(["Product", "Price"]);

// Rendering
$result = $csv->render();

// Rendering to a file
$result = $csv->render("products.csv");

// Appending to a file
$result = $csv->render("products.csv", FILE_APPEND);
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

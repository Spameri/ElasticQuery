# ElasticQuery

A PHP library that converts Elasticsearch query DSL into strongly-typed PHP objects. Instead of building queries as arrays, use type-safe classes that mirror the Elasticsearch documentation.

## Features

- **Type-safe queries** - Full-text, term-level, compound, geo, and nested queries
- **Aggregations** - Metric (min, max, avg) and bucket (terms, histogram, range, filter) aggregations
- **Response mapping** - Automatic mapping of Elasticsearch responses to typed objects
- **Index mapping** - Define index settings, analyzers, tokenizers, and filters
- **Function scoring** - Custom scoring with field value factors, weights, and random scores
- **Highlighting** - Search result highlighting support
- **Pagination & sorting** - Options for size, offset, scroll, and geo-distance sorting

## Requirements

- PHP 8.2 or higher

## Installation

Install via [Composer](http://getcomposer.org/):

```sh
composer require spameri/elastic-query
```

## Quick Start

```php
use Spameri\ElasticQuery\ElasticQuery;
use Spameri\ElasticQuery\Query\ElasticMatch;
use Spameri\ElasticQuery\Query\Term;

// Create a query
$query = new ElasticQuery();

// Add a must query (AND condition)
$query->addMustQuery(new ElasticMatch('title', 'Elasticsearch'));

// Add a filter (cached, no scoring)
$query->addFilter(new Term('status', 'published'));

// Set pagination
$query->options()->changeSize(10);
$query->options()->changeFrom(0);

// Convert to array for Elasticsearch client
$body = $query->toArray();
```

## Documentation

Learn more in the [documentation](https://github.com/Spameri/ElasticQuery/tree/master/doc):

- [Usage](https://github.com/Spameri/ElasticQuery/tree/master/doc/01-usage.md) - Integration examples
- [Query Objects](https://github.com/Spameri/ElasticQuery/tree/master/doc/02-query-objects.md) - All query types
- [Aggregation Objects](https://github.com/Spameri/ElasticQuery/tree/master/doc/03-aggregation-objects.md) - Aggregation types
- [Result Objects](https://github.com/Spameri/ElasticQuery/tree/master/doc/04-result-objects.md) - Response mapping
- [Options & Sorting](https://github.com/Spameri/ElasticQuery/tree/master/doc/05-options.md) - Pagination, sorting, scroll
- [Highlight & Function Score](https://github.com/Spameri/ElasticQuery/tree/master/doc/06-highlight-function-score.md) - Highlighting and custom scoring
- [Mapping & Settings](https://github.com/Spameri/ElasticQuery/tree/master/doc/07-mapping-settings.md) - Index configuration

## License

MIT

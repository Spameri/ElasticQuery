# ElasticQuery

A PHP library that converts Elasticsearch query DSL into strongly-typed PHP objects. Instead of building queries as arrays, use type-safe classes that mirror the Elasticsearch documentation.

## Features

- **Type-safe queries** — full-text, term-level, compound, geo, nested, joining, vector (knn / sparse_vector / semantic), span queries, and rule queries
- **Aggregations** — metric (min, max, avg, stats, weighted_avg, top_hits, top_metrics, t_test, geo_line, …), bucket (terms, histogram, date_histogram, range, filter, filters, composite with typed sources, ip_prefix, time_series, …), pipeline (cumulative_*, bucket_*, normalize, serial_diff, inference, …)
- **Function scoring** — field value factor, weight, random, decay (gauss / linear / exp), script_score; score_mode + boost_mode
- **Sort** — field, geo-distance, script-based, with nested sort (filter / max_children / recursive)
- **Highlight** — per-field config (type, fragment_size, boundary scanner, encoder, fragmenter, highlight_query, matched_fields, no_match_size, order, phrase_limit, …)
- **Search options** — `_source`, `track_total_hits`, `search_after`, `pit`, `collapse`, `rescore`, `suggest` (term / phrase / completion), `runtime_mappings`, `script_fields`, `docvalue_fields`, `stored_fields`, `terminate_after`, `timeout`, `profile`, `stats`, `ext`
- **Response mapping** — automatic mapping of Elasticsearch responses (including composite/named buckets, IP/date range buckets) to typed objects
- **Index mapping** — index settings, analyzers, tokenizers, filters

See [CHANGELOG.md](CHANGELOG.md) for the full list of types and arguments added in v2.

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

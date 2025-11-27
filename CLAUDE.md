# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ElasticQuery is a PHP library that converts Elasticsearch query DSL into typed PHP objects. Instead of building queries as arrays, developers can use strongly-typed classes that mirror the Elasticsearch documentation.

## Development Commands

```bash
# Install/update dependencies
make composer

# Run static analysis (PHPStan level 7)
make phpstan

# Run code style checks (Slevomat coding standard)
make cs

# Auto-fix code style issues
make cbf

# Run tests (Nette Tester)
make tests

# Run a single test file
vendor/bin/tester -s -p php --colors 1 -C tests/SpameriTests/ElasticQuery/Path/To/Test.phpt
```

## Architecture

### Core Query Building

**ElasticQuery** (`src/ElasticQuery.php`) - Main entry point for building queries. Composes:
- `QueryCollection` - Boolean query with must/should/mustNot collections
- `FilterCollection` - Filter context queries
- `AggregationCollection` - Aggregation definitions
- `Options` - Pagination, sorting, scroll settings
- `Highlight` - Search result highlighting
- `FunctionScore` - Custom scoring functions

All query/aggregation objects implement `toArray()` to serialize to Elasticsearch-compatible format.

### Query Objects (`src/Query/`)

Leaf queries implement `LeafQueryInterface`:
- `ElasticMatch`, `MatchPhrase`, `MultiMatch`, `PhrasePrefix` - Full-text queries
- `Term`, `Terms`, `Range`, `Exists`, `WildCard` - Term-level queries
- `Nested`, `GeoDistance`, `Fuzzy` - Specialized queries

Collection queries (`MustCollection`, `ShouldCollection`, `MustNotCollection`) also implement `LeafQueryInterface`, enabling nested boolean logic.

### Aggregations (`src/Aggregation/`)

- `LeafAggregationCollection` wraps aggregation definitions with nested sub-aggregations
- Metric aggregations: `Min`, `Max`, `Avg`, `TopHits`
- Bucket aggregations: `Term`, `Range`, `Histogram`, `Nested`, `Filter`

### Response Mapping (`src/Response/`)

`ResultMapper` converts Elasticsearch array responses to typed objects:
- `ResultSearch` - Standard search results with hits and aggregations
- `ResultSingle` - Single document retrieval
- `ResultBulk` - Bulk operation results
- `ResultVersion` - Cluster version info

### Index Mapping (`src/Mapping/`)

Classes for defining Elasticsearch index mappings:
- Analyzers: Standard, custom dictionary analyzers for multiple languages
- Filters: Stemming, synonyms, stop words, edge n-grams
- Tokenizers: Pattern, whitespace, etc.
- `Settings` - Index settings configuration

### Document Handling (`src/Document.php`, `src/Document/`)

`Document` wraps index name, body, and optional ID for Elasticsearch client calls.

### Options & Sorting (`src/Options.php`, `src/Options/`)

- `Options` - Pagination (size, from), scroll support, min_score, version inclusion
- `Sort` - Field sorting with ASC/DESC and missing value handling
- `GeoDistanceSort` - Geo-spatial sorting by distance from a point
- `SortCollection` - Manages multiple sort criteria

### Function Score (`src/FunctionScore.php`, `src/FunctionScore/`)

Custom scoring with multiple score functions:
- `FieldValueFactor` - Score based on numeric field values with modifiers (log, sqrt, etc.)
- `Weight` - Constant weight multiplier when filter matches
- `RandomScore` - Randomized scoring with optional seed for consistency

Score modes: multiply, sum, avg, first, max, min

## Coding Standards

- PHP 8.2+ with strict types
- Fully qualified class names in annotations
- Fully qualified global functions and constants
- Tab indentation (no spaces)
- Trailing commas in arrays, function calls, and declarations
- Constructor property promotion where applicable
- No Yoda comparisons
- Strict equality operators only (`===`, `!==`)

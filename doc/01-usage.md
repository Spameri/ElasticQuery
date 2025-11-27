# Usage

This guide shows how to use ElasticQuery with different Elasticsearch clients.

## Basic Concepts

`\Spameri\ElasticQuery\ElasticQuery` is the main entry point for building queries. It composes:

- **query()** - Boolean query context (must, should, mustNot) - affects scoring
- **filter()** - Filter context - cached queries that don't affect scoring
- **aggregation()** - Aggregation definitions
- **options()** - Pagination, sorting, scroll settings
- **highlight()** - Search result highlighting
- **functionScore()** - Custom scoring functions

Every query object implements `\Spameri\ElasticQuery\Query\LeafQueryInterface` and can be nested. Collections (MustCollection, ShouldCollection, MustNotCollection) also implement this interface, enabling complex nested boolean logic.

## Building Queries

### Simple Query
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();
$query->addMustQuery(
	new \Spameri\ElasticQuery\Query\ElasticMatch('name', 'Avengers')
);
```

### Boolean Query with Multiple Conditions
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Must match (AND)
$query->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'elasticsearch'));

// Should match (OR) - boosts score if matched
$query->addShouldQuery(new \Spameri\ElasticQuery\Query\Term('category', 'tutorial'));

// Must not match (NOT)
$query->addMustNotQuery(new \Spameri\ElasticQuery\Query\Term('status', 'draft'));
```

### Using Filters (No Scoring, Cached)
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Filters don't affect scoring and are cached
$query->addFilter(new \Spameri\ElasticQuery\Query\Term('status', 'published'));
$query->addFilter(new \Spameri\ElasticQuery\Query\Range('date', gte: '2024-01-01'));
```

### Nested Boolean Logic
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Create a nested should collection
$shouldCollection = new \Spameri\ElasticQuery\Query\ShouldCollection();
$shouldCollection->add(new \Spameri\ElasticQuery\Query\Term('category', 'books'));
$shouldCollection->add(new \Spameri\ElasticQuery\Query\Term('category', 'movies'));

// Add the nested collection as a must condition
$query->query()->must()->add($shouldCollection);
```

### With Aggregations
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();
$query->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('content', 'search'));

// Add term aggregation
$query->addAggregation(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'categories',
		new \Spameri\ElasticQuery\Aggregation\Term('category')
	)
);
```

### With Pagination and Sorting
```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Set pagination
$query->options()->changeSize(20);
$query->options()->changeFrom(40); // Skip first 40 results

// Add sorting
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\Sort('date', \Spameri\ElasticQuery\Options\Sort::DESC)
);
```

---

# Using with [Elasticsearch/Elasticsearch](https://github.com/elastic/elasticsearch-php)

Set up the document to send to the Elasticsearch library:
```php
$document = new \Spameri\ElasticQuery\Document(
	'spameri_video',
	new \Spameri\ElasticQuery\Document\Body\Plain($query->toArray())
);
```

The Document constructor accepts:
1. Index name (required)
2. Body object (required)
3. Type (optional, for older Elasticsearch versions)
4. ID (optional, for single document operations)

Send the document to the Elasticsearch client:
```php
$response = $client->search($document->toArray());
```

---

# Response Mapping

Elasticsearch returns array responses. Map them to typed objects:

### Automatic Mapping
```php
$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
$resultObject = $resultMapper->map($response);
```

The mapper auto-detects the response type and returns the appropriate `ResultInterface` implementation.

### Specific Mapping Methods

For single document retrieval (by ID):
```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSingleResult($response);
// Returns: ResultSingle
```

For search responses with multiple hits:
```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);
// Returns: ResultSearch with hits and aggregations
```

For bulk operation results:
```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapBulkResult($response);
// Returns: ResultBulk with action statuses
```

For cluster version info:
```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapVersionResult($response);
// Returns: ResultVersion
```

---

# Using with Spameri/Elastic

This is an implementation for Nette framework. [Setup guide](https://github.com/Spameri/Elastic/blob/master/doc/01_intro.md#1-config-elasticsearch)

```php
$response = $this->clientProvider->client()->search(
	$document->toArray()
);
```

Query construction and response mapping are the same. For advanced usage, see the [Spameri/Elastic documentation](https://github.com/Spameri/Elastic/blob/master/doc/01_intro.md).

---

# Using with Guzzle

With Guzzle, specify the index in the URL directly:
```php
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://localhost:9200/spameri_video/_search', [
	'body' => \json_encode($query->toArray()),
	'headers' => ['Content-Type' => 'application/json'],
]);

$data = \json_decode($response->getBody()->getContents(), true);
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($data);
```

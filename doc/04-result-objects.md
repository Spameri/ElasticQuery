# Result Objects

Every response object implements `\Spameri\ElasticQuery\Response\ResultInterface` and provides typed access to Elasticsearch responses.

---

## Result Mapper

The `ResultMapper` class automatically detects response types and maps them:

```php
$resultMapper = new \Spameri\ElasticQuery\Response\ResultMapper();
$result = $resultMapper->map($response);
```

Or use specific mapping methods:

```php
// Single document
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSingleResult($response);

// Search results
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);

// Bulk operations
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapBulkResult($response);

// Cluster version
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapVersionResult($response);
```

---

## Result Types

##### ResultSearch
Search results with hits and aggregations.
- Class: `\Spameri\ElasticQuery\Response\ResultSearch`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultSearch.php)

```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);

// Access statistics
$result->stats()->took();      // Query execution time in ms
$result->stats()->totalHits(); // Total matching documents

// Access shard info
$result->shards()->total();
$result->shards()->successful();
$result->shards()->failed();

// Iterate over hits
foreach ($result->hits() as $hit) {
	$hit->id();       // Document ID
	$hit->index();    // Index name
	$hit->score();    // Relevance score
	$hit->source();   // Document source (array)
	$hit->highlight(); // Highlighted fields (if requested)
}

// Get specific hit by ID
$hit = $result->getHit('document_id');

// Access aggregations
foreach ($result->aggregations() as $aggregation) {
	$aggregation->name();    // Aggregation name
	$aggregation->buckets(); // Bucket collection
	$aggregation->value();   // Metric value (for metric aggregations)
	$aggregation->hits();    // Hits (for top_hits aggregation)
}

// Get specific aggregation by name
$categoryAgg = $result->getAggregation('categories');
foreach ($categoryAgg->buckets() as $bucket) {
	$bucket->key();      // Bucket key (e.g., category name)
	$bucket->docCount(); // Number of documents in bucket
}
```

##### ResultSingle
Single document retrieval result.
- Class: `\Spameri\ElasticQuery\Response\ResultSingle`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultSingle.php)

```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSingleResult($response);

// Access the hit
$hit = $result->hit();
$hit->id();
$hit->index();
$hit->source();
$hit->version(); // If version was requested

// Access stats
$result->stats()->found(); // Boolean - document exists
```

##### ResultBulk
Bulk operation results.
- Class: `\Spameri\ElasticQuery\Response\ResultBulk`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultBulk.php)

```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapBulkResult($response);

// Access stats
$result->stats()->took();
$result->stats()->errors(); // Boolean - any errors occurred

// Get specific action by ID
$action = $result->getFirstAction('document_id');
$action->id();
$action->index();
$action->result();  // 'created', 'updated', 'deleted', etc.
$action->status();  // HTTP status code
```

##### ResultVersion
Cluster version information.
- Class: `\Spameri\ElasticQuery\Response\ResultVersion`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Response/ResultVersion.php)

```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapVersionResult($response);

$result->name();        // Node name
$result->clusterName(); // Cluster name
$result->clusterUUID(); // Cluster UUID
$result->tagLine();     // "You Know, for Search"

// Version details
$result->version()->number();             // e.g., "8.11.0"
$result->version()->buildFlavor();        // e.g., "default"
$result->version()->buildType();          // e.g., "docker"
$result->version()->luceneVersion();      // e.g., "9.8.0"
$result->version()->minimumWireVersion();
$result->version()->minimumIndexVersion();
```

---

## Supporting Classes

##### Hit
Individual search result document.
- Class: `\Spameri\ElasticQuery\Response\Result\Hit`

##### HitCollection
Collection of search result hits.
- Class: `\Spameri\ElasticQuery\Response\Result\HitCollection`

##### Aggregation
Aggregation result with buckets or metric value.
- Class: `\Spameri\ElasticQuery\Response\Result\Aggregation`

##### AggregationCollection
Collection of aggregation results.
- Class: `\Spameri\ElasticQuery\Response\Result\AggregationCollection`

##### Bucket
Single aggregation bucket.
- Class: `\Spameri\ElasticQuery\Response\Result\Aggregation\Bucket`

##### BucketCollection
Collection of aggregation buckets.
- Class: `\Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection`

##### Stats / StatsSingle
Query execution statistics.
- Class: `\Spameri\ElasticQuery\Response\Stats`
- Class: `\Spameri\ElasticQuery\Response\StatsSingle`

##### Shards
Shard execution information.
- Class: `\Spameri\ElasticQuery\Response\Shards`

---

## Complete Example

```php
// Execute search
$response = $client->search($document->toArray());

// Map to typed result
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);

// Display results
echo "Found {$result->stats()->totalHits()} documents in {$result->stats()->took()}ms\n";

foreach ($result->hits() as $hit) {
	echo "- [{$hit->id()}] Score: {$hit->score()}\n";
	echo "  Title: {$hit->source()['title']}\n";

	// Display highlights if available
	if ($hit->highlight()) {
		foreach ($hit->highlight() as $field => $fragments) {
			echo "  Highlight ({$field}): " . implode('...', $fragments) . "\n";
		}
	}
}

// Display aggregations
if ($result->aggregations()->count() > 0) {
	$categoryAgg = $result->getAggregation('categories');
	echo "\nCategories:\n";
	foreach ($categoryAgg->buckets() as $bucket) {
		echo "- {$bucket->key()}: {$bucket->docCount()} documents\n";
	}
}
```

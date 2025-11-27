# Aggregation Objects

Every aggregation object implements `\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface` and is capable of converting to an array.

Aggregations are wrapped in `LeafAggregationCollection` which allows nesting sub-aggregations.

---

## Using Aggregations

Aggregations are added to the query using `LeafAggregationCollection`:

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Add a simple term aggregation
$query->addAggregation(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'categories',                                      // Aggregation name
		new \Spameri\ElasticQuery\Aggregation\Term('category') // Aggregation definition
	)
);
```

### Nested Aggregations

```php
// Term aggregation with nested avg sub-aggregation
$categoryAgg = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
	'by_category',
	new \Spameri\ElasticQuery\Aggregation\Term('category')
);

// Add sub-aggregation
$categoryAgg->subAggregation()->add(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'avg_price',
		new \Spameri\ElasticQuery\Aggregation\Avg('price')
	)
);

$query->addAggregation($categoryAgg);
```

---

## Metric Aggregations

Compute metrics over a set of documents.

##### Min Aggregation
Returns the minimum value of a numeric field.
- Class: `\Spameri\ElasticQuery\Aggregation\Min`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-min-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Min.php)

```php
new \Spameri\ElasticQuery\Aggregation\Min(field: 'price');
```

##### Max Aggregation
Returns the maximum value of a numeric field.
- Class: `\Spameri\ElasticQuery\Aggregation\Max`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-max-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Max.php)

```php
new \Spameri\ElasticQuery\Aggregation\Max(field: 'price');
```

##### Avg Aggregation
Returns the average value of a numeric field.
- Class: `\Spameri\ElasticQuery\Aggregation\Avg`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-avg-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Avg.php)

```php
new \Spameri\ElasticQuery\Aggregation\Avg(field: 'price');
```

##### TopHits Aggregation
Returns the top matching documents per bucket.
- Class: `\Spameri\ElasticQuery\Aggregation\TopHits`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/TopHits.php)

```php
new \Spameri\ElasticQuery\Aggregation\TopHits(size: 3); // Return top 3 hits per bucket
```

---

## Bucket Aggregations

Group documents into buckets.

##### Term Aggregation
Groups documents by unique field values.
- Class: `\Spameri\ElasticQuery\Aggregation\Term`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Term.php)

```php
new \Spameri\ElasticQuery\Aggregation\Term(
	field: 'category',
	size: 10, // Return top 10 terms
);
```

##### Histogram Aggregation
Groups documents into fixed-width numeric intervals.
- Class: `\Spameri\ElasticQuery\Aggregation\Histogram`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Histogram.php)

```php
new \Spameri\ElasticQuery\Aggregation\Histogram(
	field: 'price',
	interval: 50, // Buckets: 0-50, 50-100, 100-150, etc.
);
```

##### Range Aggregation
Groups documents into manually defined ranges.
- Class: `\Spameri\ElasticQuery\Aggregation\Range`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Range.php)

```php
$rangeValues = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
	new \Spameri\ElasticQuery\Aggregation\RangeValue(to: 50),        // 0-50
	new \Spameri\ElasticQuery\Aggregation\RangeValue(from: 50, to: 100), // 50-100
	new \Spameri\ElasticQuery\Aggregation\RangeValue(from: 100),    // 100+
);

new \Spameri\ElasticQuery\Aggregation\Range(
	field: 'price',
	rangeValues: $rangeValues,
);
```

##### Filter Aggregation
Single bucket containing documents matching a filter query.
- Class: `\Spameri\ElasticQuery\Aggregation\Filter`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Filter.php)

```php
$filterAgg = new \Spameri\ElasticQuery\Aggregation\Filter();
$filterAgg->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'published'));
```

##### Nested Aggregation
Aggregates on nested documents.
- Class: `\Spameri\ElasticQuery\Aggregation\Nested`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-nested-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Nested.php)

```php
new \Spameri\ElasticQuery\Aggregation\Nested(path: 'comments');
```

---

## Aggregation Collections

##### AggregationCollection
Top-level container for aggregations in a query.
- Class: `\Spameri\ElasticQuery\Aggregation\AggregationCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/AggregationCollection.php)

##### LeafAggregationCollection
Wrapper for individual aggregations that enables naming and sub-aggregations.
- Class: `\Spameri\ElasticQuery\Aggregation\LeafAggregationCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/LeafAggregationCollection.php)

```php
$agg = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
	'aggregation_name',
	new \Spameri\ElasticQuery\Aggregation\Term('field'),
);

// Access sub-aggregations
$agg->subAggregation()->add(/* nested aggregation */);
```

---

## Complete Example

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();
$query->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('content', 'elasticsearch'));

// Category aggregation with nested stats
$categoryAgg = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
	'categories',
	new \Spameri\ElasticQuery\Aggregation\Term('category', 20)
);

// Add sub-aggregations for each category bucket
$categoryAgg->subAggregation()->add(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'min_price',
		new \Spameri\ElasticQuery\Aggregation\Min('price')
	)
);
$categoryAgg->subAggregation()->add(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'max_price',
		new \Spameri\ElasticQuery\Aggregation\Max('price')
	)
);
$categoryAgg->subAggregation()->add(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'avg_price',
		new \Spameri\ElasticQuery\Aggregation\Avg('price')
	)
);

$query->addAggregation($categoryAgg);

// Price histogram
$query->addAggregation(
	new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
		'price_ranges',
		new \Spameri\ElasticQuery\Aggregation\Histogram('price', 100)
	)
);
```
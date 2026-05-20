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

##### Sum Aggregation
Returns the sum of values of a numeric field.
- Class: `\Spameri\ElasticQuery\Aggregation\Sum`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-sum-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Sum.php)

```php
new \Spameri\ElasticQuery\Aggregation\Sum(field: 'price');
```

##### ValueCount Aggregation
Counts the number of values extracted from a field.
- Class: `\Spameri\ElasticQuery\Aggregation\ValueCount`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-valuecount-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/ValueCount.php)

```php
new \Spameri\ElasticQuery\Aggregation\ValueCount(field: 'price');
```

##### Stats Aggregation
Returns count, min, max, avg and sum in one call.
- Class: `\Spameri\ElasticQuery\Aggregation\Stats`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-stats-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Stats.php)

```php
new \Spameri\ElasticQuery\Aggregation\Stats(field: 'price');
```

##### ExtendedStats Aggregation
Stats plus variance, standard deviation and bounds.
- Class: `\Spameri\ElasticQuery\Aggregation\ExtendedStats`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-extendedstats-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/ExtendedStats.php)

```php
new \Spameri\ElasticQuery\Aggregation\ExtendedStats(
	field: 'price',
	sigma: 3.0, // Optional, default 2.0
);
```

##### Percentiles Aggregation
Calculates percentile values (e.g. p50, p95, p99) over a numeric field.
- Class: `\Spameri\ElasticQuery\Aggregation\Percentiles`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Percentiles.php)

```php
new \Spameri\ElasticQuery\Aggregation\Percentiles(
	field: 'load_time',
	percents: [50, 95, 99], // Optional, default [1, 5, 25, 50, 75, 95, 99]
);
```

##### PercentileRanks Aggregation
Calculates the percentile rank for given values.
- Class: `\Spameri\ElasticQuery\Aggregation\PercentileRanks`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-rank-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/PercentileRanks.php)

```php
new \Spameri\ElasticQuery\Aggregation\PercentileRanks(
	field: 'load_time',
	values: [500, 600],
);
```

##### WeightedAvg Aggregation
Computes a weighted average over two fields (value and weight).
- Class: `\Spameri\ElasticQuery\Aggregation\WeightedAvg`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-weight-avg-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/WeightedAvg.php)

```php
new \Spameri\ElasticQuery\Aggregation\WeightedAvg(
	valueField: 'grade',
	weightField: 'weight',
);
```

##### MedianAbsoluteDeviation Aggregation
Computes a robust measure of variability via the median of absolute deviations from the median.
- Class: `\Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-median-absolute-deviation-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/MedianAbsoluteDeviation.php)

```php
new \Spameri\ElasticQuery\Aggregation\MedianAbsoluteDeviation(field: 'rating');
```

##### StringStats Aggregation
Computes statistics over string values (length, character distribution).
- Class: `\Spameri\ElasticQuery\Aggregation\StringStats`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-string-stats-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/StringStats.php)

```php
new \Spameri\ElasticQuery\Aggregation\StringStats(
	field: 'message.keyword',
	showDistribution: true, // Optional, include per-character frequencies
);
```

##### BoxPlot Aggregation
Computes min, max, median and quartiles for plotting box-and-whisker diagrams.
- Class: `\Spameri\ElasticQuery\Aggregation\BoxPlot`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-boxplot-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/BoxPlot.php)

```php
new \Spameri\ElasticQuery\Aggregation\BoxPlot(field: 'load_time');
```

##### GeoCentroid Aggregation
Computes the weighted centroid of a set of geo points.
- Class: `\Spameri\ElasticQuery\Aggregation\GeoCentroid`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geocentroid-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GeoCentroid.php)

```php
new \Spameri\ElasticQuery\Aggregation\GeoCentroid(field: 'location');
```

##### GeoBounds Aggregation
Computes the bounding box of all matching geo points.
- Class: `\Spameri\ElasticQuery\Aggregation\GeoBounds`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geobounds-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GeoBounds.php)

```php
new \Spameri\ElasticQuery\Aggregation\GeoBounds(
	field: 'location',
	wrapLongitude: true, // Default true, allow boxes crossing the dateline
);
```

##### Cardinality Aggregation
Approximate count of distinct values using HyperLogLog++.
- Class: `\Spameri\ElasticQuery\Aggregation\Cardinality`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-cardinality-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Cardinality.php)

```php
new \Spameri\ElasticQuery\Aggregation\Cardinality(
	field: 'user_id',
	precisionThreshold: 3000, // Optional, default 3000, max 40000
);
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

##### DateHistogram Aggregation
Groups documents into date-based intervals (calendar or fixed).
- Class: `\Spameri\ElasticQuery\Aggregation\DateHistogram`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-datehistogram-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/DateHistogram.php)

```php
new \Spameri\ElasticQuery\Aggregation\DateHistogram(
	field: 'created_at',
	calendarInterval: 'month', // or fixedInterval: '7d'
	format: 'yyyy-MM-dd',
	timeZone: 'Europe/Prague',
	minDocCount: 1,
);
```

##### DateRange Aggregation
Groups documents into date ranges (accepts relative dates like `now-1M/M`).
- Class: `\Spameri\ElasticQuery\Aggregation\DateRange`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-daterange-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/DateRange.php)

```php
$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
	new \Spameri\ElasticQuery\Aggregation\RangeValue('past', null, 'now-1M/M'),
	new \Spameri\ElasticQuery\Aggregation\RangeValue('recent', 'now-1M/M', null),
);

new \Spameri\ElasticQuery\Aggregation\DateRange(
	field: 'created_at',
	ranges: $ranges,
	format: 'MM-yyyy',
);
```

##### Missing Aggregation
Single bucket containing documents missing a field value.
- Class: `\Spameri\ElasticQuery\Aggregation\Missing`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-missing-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Missing.php)

```php
new \Spameri\ElasticQuery\Aggregation\Missing(field: 'price');
```

##### Global Aggregation
Single bucket containing all documents, ignoring the current query.
- Class: `\Spameri\ElasticQuery\Aggregation\GlobalAggregation`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-global-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GlobalAggregation.php)

```php
new \Spameri\ElasticQuery\Aggregation\GlobalAggregation();
```

##### SignificantTerms Aggregation
Finds terms that occur unusually often within the query context vs the index as a whole.
- Class: `\Spameri\ElasticQuery\Aggregation\SignificantTerms`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significantterms-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/SignificantTerms.php)

```php
new \Spameri\ElasticQuery\Aggregation\SignificantTerms(
	field: 'crime_type',
	size: 10,
	minDocCount: 5,
);
```

##### SignificantText Aggregation
Like significant terms but optimised for free-text fields.
- Class: `\Spameri\ElasticQuery\Aggregation\SignificantText`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significanttext-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/SignificantText.php)

```php
new \Spameri\ElasticQuery\Aggregation\SignificantText(
	field: 'content',
	size: 20,
	filterDuplicateText: true,
);
```

##### GeoDistance Aggregation
Groups documents into concentric distance buckets around an origin point.
- Class: `\Spameri\ElasticQuery\Aggregation\GeoDistance`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geodistance-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GeoDistance.php)

```php
$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
	new \Spameri\ElasticQuery\Aggregation\RangeValue('near', null, 100),
	new \Spameri\ElasticQuery\Aggregation\RangeValue('far', 100, null),
);

new \Spameri\ElasticQuery\Aggregation\GeoDistance(
	field: 'location',
	lat: 50.0,
	lon: 14.4,
	ranges: $ranges,
	unit: 'km',
);
```

##### GeoHashGrid Aggregation
Groups geo points into geohash-prefixed cells of configurable precision.
- Class: `\Spameri\ElasticQuery\Aggregation\GeoHashGrid`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geohashgrid-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GeoHashGrid.php)

```php
new \Spameri\ElasticQuery\Aggregation\GeoHashGrid(
	field: 'location',
	precision: 5,
);
```

##### GeoTileGrid Aggregation
Groups geo points into map-tile cells (zoom levels 0–29).
- Class: `\Spameri\ElasticQuery\Aggregation\GeoTileGrid`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geotilegrid-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/GeoTileGrid.php)

```php
new \Spameri\ElasticQuery\Aggregation\GeoTileGrid(
	field: 'location',
	precision: 8,
);
```

##### Nested Aggregation
Aggregates on nested documents.
- Class: `\Spameri\ElasticQuery\Aggregation\Nested`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-nested-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Nested.php)

```php
new \Spameri\ElasticQuery\Aggregation\Nested(path: 'comments');
```

##### Composite Aggregation
Paginated multi-source buckets — useful for retrieving all unique value combinations.
- Class: `\Spameri\ElasticQuery\Aggregation\Composite`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-composite-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Composite.php)

```php
$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
	key: 'my_buckets',
	source: new \Spameri\ElasticQuery\Aggregation\Term('product'),
	size: 100,
);
$composite->addSource(new \Spameri\ElasticQuery\Aggregation\Histogram('price', 50));
// $composite->addSource(new \Spameri\ElasticQuery\Aggregation\DateHistogram('date', calendarInterval: 'day'));
```

##### MultiTerms Aggregation
Groups documents by the combination of values from multiple fields.
- Class: `\Spameri\ElasticQuery\Aggregation\MultiTerms`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-multi-terms-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/MultiTerms.php)

```php
new \Spameri\ElasticQuery\Aggregation\MultiTerms(
	terms: ['brand', 'color'],
	size: 10,
);
```

##### RareTerms Aggregation
Finds terms that occur infrequently (the opposite of a terms-with-large-min-doc-count search).
- Class: `\Spameri\ElasticQuery\Aggregation\RareTerms`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-rare-terms-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/RareTerms.php)

```php
new \Spameri\ElasticQuery\Aggregation\RareTerms(
	field: 'genre',
	maxDocCount: 2,
);
```

##### Sampler Aggregation
Limits sub-aggregations to top-N highest-scoring documents per shard.
- Class: `\Spameri\ElasticQuery\Aggregation\Sampler`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-sampler-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Sampler.php)

```php
new \Spameri\ElasticQuery\Aggregation\Sampler(shardSize: 200);
```

##### DiversifiedSampler Aggregation
Like sampler but limits documents-per-distinct-value to avoid skew.
- Class: `\Spameri\ElasticQuery\Aggregation\DiversifiedSampler`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-diversified-sampler-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/DiversifiedSampler.php)

```php
new \Spameri\ElasticQuery\Aggregation\DiversifiedSampler(
	field: 'author.keyword',
	shardSize: 200,
	maxDocsPerValue: 3,
);
```

##### AdjacencyMatrix Aggregation
Buckets for each named filter and each pairwise intersection.
- Class: `\Spameri\ElasticQuery\Aggregation\AdjacencyMatrix`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-adjacency-matrix-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/AdjacencyMatrix.php)

```php
$filterA = new \Spameri\ElasticQuery\Filter\FilterCollection();
$filterA->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));

$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix();
$matrix->addFilter('group_a', $filterA);
```

##### IpRange Aggregation
Groups IP-typed fields into ranges (accepts plain IPs or CIDR masks).
- Class: `\Spameri\ElasticQuery\Aggregation\IpRange`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-iprange-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/IpRange.php)

```php
$ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(
	new \Spameri\ElasticQuery\Aggregation\RangeValue('low', null, '10.0.0.5'),
	new \Spameri\ElasticQuery\Aggregation\RangeValue('high', '10.0.0.5', null),
);

new \Spameri\ElasticQuery\Aggregation\IpRange(
	field: 'ip',
	ranges: $ranges,
);
```

##### ReverseNested Aggregation
Moves back from a nested context to the parent (or an ancestor at `path`).
- Class: `\Spameri\ElasticQuery\Aggregation\ReverseNested`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-reverse-nested-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/ReverseNested.php)

```php
new \Spameri\ElasticQuery\Aggregation\ReverseNested();
// Or back to a specific ancestor path:
new \Spameri\ElasticQuery\Aggregation\ReverseNested(path: 'parent_field');
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
# Aggregation objects

Every object is as close to documentation as possible. Also reference provided in doc blocks where possible.
[Like this](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Term.php#L7)

Every aggregation object implements `\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface` and is capable of converting to array.

AggregationCollection is also **LeafAggregationInterface**, so you can nest as you need.

## Implementations
##### Term Aggregation
- Class `\Spameri\ElasticQuery\Aggregation\Term`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Term.php)

##### Histogram Aggregation
- Class `\Spameri\ElasticQuery\Aggregation\Histogram`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Histogram.php)

##### Range Aggregation
- Class `\Spameri\ElasticQuery\Aggregation\Range`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/Range.php)

##### AggregationCollection Aggregation
- Class `\Spameri\ElasticQuery\Aggregation\AggregationCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/AggregationCollection.php)
- Top level aggregations.

##### LeafAggregationCollection Aggregation
- Class `\Spameri\ElasticQuery\Aggregation\LeafAggregationCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Aggregation/LeafAggregationCollection.php)
- Nested aggregations collection.
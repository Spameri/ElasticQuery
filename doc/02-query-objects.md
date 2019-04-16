# Query objects

Every object is as close to documentation as possible. Also reference provided in doc blocks where possible.
[Like this](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Match.php#L7)

Every query object implements `\Spameri\ElasticQuery\Query\LeafQueryInterface` and is capable of converting to array.

Must and should collection of objects is also **LeafQueryInterface**, so you can nest rules as you need.

## Implementations
##### Match Query
- Class `\Spameri\ElasticQuery\Query\Match`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Match.php)
- [Sample usage](https://github.com/Spameri/ElasticQuery/blob/master/tests/SpameriTests/ElasticQuery/Query/Match.phpt#L8)

##### Fuzzy Query
- Class `\Spameri\ElasticQuery\Query\Fuzzy`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Fuzzy.php)

##### Range Query
- Class `\Spameri\ElasticQuery\Query\Range`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Range.php)

##### Term Query
- Class `\Spameri\ElasticQuery\Query\Term`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Term.php)

##### Terms Query
- Class `\Spameri\ElasticQuery\Query\Terms`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Terms.php)

##### WildCard Query
- Class `\Spameri\ElasticQuery\Query\WildCard`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/WildCard.php)

##### QueryCollection Query
- Class `\Spameri\ElasticQuery\Query\QueryCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/QueryCollection.php)
- This is query for nesting your must, must_not and should sub queries.

##### MustCollection Query
- Class `\Spameri\ElasticQuery\Query\MustCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MustCollection.php)

##### MustNotCollection Query
- Class `\Spameri\ElasticQuery\Query\MustNotCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MustNotCollection.php)

##### ShouldCollection Query
- Class `\Spameri\ElasticQuery\Query\ShouldCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/ShouldCollection.php)

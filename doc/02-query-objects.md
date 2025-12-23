# Query Objects

Every query object implements `\Spameri\ElasticQuery\Query\LeafQueryInterface` and is capable of converting to an array for Elasticsearch.

Collections (MustCollection, ShouldCollection, MustNotCollection) also implement **LeafQueryInterface**, allowing nested boolean logic.

---

## Full-text Queries

##### Match Query
Full-text search with analysis on the query string.
- Class: `\Spameri\ElasticQuery\Query\ElasticMatch`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/ElasticMatch.php)

```php
new \Spameri\ElasticQuery\Query\ElasticMatch(
	field: 'title',
	query: 'quick brown fox',
	boost: 1.0,
	fuzziness: new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO'),
	operator: \Spameri\ElasticQuery\Query\Match\Operator::AND,
);
```

##### MatchPhrase Query
Matches exact phrases in order.
- Class: `\Spameri\ElasticQuery\Query\MatchPhrase`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MatchPhrase.php)

```php
new \Spameri\ElasticQuery\Query\MatchPhrase(
	field: 'content',
	query: 'quick brown fox',
	boost: 1.0,
	slop: 2, // Allow 2 words between terms
);
```

##### PhrasePrefix Query
Matches phrases with prefix matching on the last term. Useful for autocomplete.
- Class: `\Spameri\ElasticQuery\Query\PhrasePrefix`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase-prefix.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/PhrasePrefix.php)

```php
new \Spameri\ElasticQuery\Query\PhrasePrefix(
	field: 'title',
	queryString: 'quick bro', // Matches "quick brown", "quick brother", etc.
	boost: 1,
	slop: 1,
);
```

##### MultiMatch Query
Search across multiple fields with different matching strategies.
- Class: `\Spameri\ElasticQuery\Query\MultiMatch`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MultiMatch.php)

```php
new \Spameri\ElasticQuery\Query\MultiMatch(
	fields: ['title^2', 'content', 'tags'], // title has 2x boost
	query: 'elasticsearch guide',
	boost: 1.0,
	fuzziness: null,
	type: \Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
	minimumShouldMatch: 2,
	operator: \Spameri\ElasticQuery\Query\Match\Operator::OR,
	analyzer: 'standard',
);
```

MultiMatch types: `BEST_FIELDS`, `MOST_FIELDS`, `CROSS_FIELDS`, `PHRASE`, `PHRASE_PREFIX`, `BOOL_PREFIX`

##### Fuzzy Query
Matches terms similar to the search term using Levenshtein distance.
- Class: `\Spameri\ElasticQuery\Query\Fuzzy`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Fuzzy.php)

```php
new \Spameri\ElasticQuery\Query\Fuzzy(
	field: 'name',
	value: 'elasticsearh', // Typo - still matches "elasticsearch"
);
```

---

## Term-level Queries

##### Term Query
Exact match on a field value (not analyzed).
- Class: `\Spameri\ElasticQuery\Query\Term`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Term.php)

```php
new \Spameri\ElasticQuery\Query\Term(
	field: 'status',
	value: 'published',
	boost: 1.0,
);
```

##### Terms Query
Match any of multiple exact values.
- Class: `\Spameri\ElasticQuery\Query\Terms`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Terms.php)

```php
new \Spameri\ElasticQuery\Query\Terms(
	field: 'category',
	values: ['books', 'movies', 'music'],
);
```

##### Range Query
Match values within a specified range.
- Class: `\Spameri\ElasticQuery\Query\Range`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Range.php)

```php
new \Spameri\ElasticQuery\Query\Range(
	field: 'price',
	gte: 10,      // Greater than or equal
	lte: 100,     // Less than or equal
	gt: null,     // Greater than (exclusive)
	lt: null,     // Less than (exclusive)
	boost: 1.0,
);

// Date range example
new \Spameri\ElasticQuery\Query\Range(
	field: 'created_at',
	gte: '2024-01-01',
	lte: 'now',
);
```

##### Exists Query
Match documents where a field has a value.
- Class: `\Spameri\ElasticQuery\Query\Exists`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Exists.php)

```php
new \Spameri\ElasticQuery\Query\Exists(field: 'description');
```

##### WildCard Query
Match using wildcard patterns (* and ?).
- Class: `\Spameri\ElasticQuery\Query\WildCard`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/WildCard.php)

```php
new \Spameri\ElasticQuery\Query\WildCard(
	field: 'email',
	value: '*@example.com', // Matches any email ending with @example.com
);
```

---

## Specialized Queries

##### MatchAll Query
Matches all documents in the index. Useful as a base query for filtering or function scoring.
- Class: `\Spameri\ElasticQuery\Query\MatchAll`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-all-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MatchAll.php)

```php
// Match all documents
new \Spameri\ElasticQuery\Query\MatchAll();

// Match all with custom boost
new \Spameri\ElasticQuery\Query\MatchAll(boost: 1.5);
```

##### Nested Query
Query nested objects with their own scope.
- Class: `\Spameri\ElasticQuery\Query\Nested`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/Nested.php)

```php
$nested = new \Spameri\ElasticQuery\Query\Nested(path: 'comments');
$nested->getQuery()->must()->add(
	new \Spameri\ElasticQuery\Query\Term('comments.author', 'john')
);
$nested->getQuery()->must()->add(
	new \Spameri\ElasticQuery\Query\Range('comments.date', gte: '2024-01-01')
);
```

##### GeoDistance Query
Match documents within a geographic distance from a point.
- Class: `\Spameri\ElasticQuery\Query\GeoDistance`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/GeoDistance.php)

```php
new \Spameri\ElasticQuery\Query\GeoDistance(
	field: 'location',
	lat: 40.7128,
	lon: -74.0060,
);
```

---

## Boolean Query Collections

These collections implement `LeafQueryInterface` and can be nested arbitrarily.

##### QueryCollection
Container for must, should, and mustNot sub-queries.
- Class: `\Spameri\ElasticQuery\Query\QueryCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/QueryCollection.php)

```php
$queryCollection = new \Spameri\ElasticQuery\Query\QueryCollection();
$queryCollection->must()->add(new \Spameri\ElasticQuery\Query\Term('status', 'active'));
$queryCollection->should()->add(new \Spameri\ElasticQuery\Query\Term('featured', true));
$queryCollection->mustNot()->add(new \Spameri\ElasticQuery\Query\Term('deleted', true));
```

##### MustCollection
AND logic - all queries must match.
- Class: `\Spameri\ElasticQuery\Query\MustCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MustCollection.php)

##### ShouldCollection
OR logic - at least one query should match (affects scoring).
- Class: `\Spameri\ElasticQuery\Query\ShouldCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/ShouldCollection.php)

##### MustNotCollection
NOT logic - queries must not match.
- Class: `\Spameri\ElasticQuery\Query\MustNotCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Query/MustNotCollection.php)

### Nested Boolean Example

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Must be published AND (category = books OR category = movies)
$query->addFilter(new \Spameri\ElasticQuery\Query\Term('status', 'published'));

$categoryShould = new \Spameri\ElasticQuery\Query\ShouldCollection();
$categoryShould->add(new \Spameri\ElasticQuery\Query\Term('category', 'books'));
$categoryShould->add(new \Spameri\ElasticQuery\Query\Term('category', 'movies'));

$query->query()->must()->add($categoryShould);
```

---

## Helper Classes

##### Fuzziness
Configure fuzzy matching behavior.
- Class: `\Spameri\ElasticQuery\Query\Match\Fuzziness`

```php
new \Spameri\ElasticQuery\Query\Match\Fuzziness('AUTO'); // Auto-detect based on term length
new \Spameri\ElasticQuery\Query\Match\Fuzziness('2');    // Allow 2 edits
```

##### Operator
Boolean operator for multi-term queries.
- Class: `\Spameri\ElasticQuery\Query\Match\Operator`

```php
\Spameri\ElasticQuery\Query\Match\Operator::AND // All terms must match
\Spameri\ElasticQuery\Query\Match\Operator::OR  // Any term can match (default)
```

##### MultiMatchType
Matching strategy for multi-match queries.
- Class: `\Spameri\ElasticQuery\Query\Match\MultiMatchType`

```php
\Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS   // Default - highest score from any field
\Spameri\ElasticQuery\Query\Match\MultiMatchType::MOST_FIELDS   // Combine scores from all fields
\Spameri\ElasticQuery\Query\Match\MultiMatchType::CROSS_FIELDS  // Treat fields as one big field
\Spameri\ElasticQuery\Query\Match\MultiMatchType::PHRASE        // Run match_phrase on each field
\Spameri\ElasticQuery\Query\Match\MultiMatchType::PHRASE_PREFIX // Run match_phrase_prefix on each field
\Spameri\ElasticQuery\Query\Match\MultiMatchType::BOOL_PREFIX   // Create bool query with term/prefix queries
```

# Options, Pagination & Sorting

The `Options` class and related sorting classes control pagination, result ordering, scrolling, and other search options.

---

## Options

Access options through the ElasticQuery:

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();
$options = $query->options();
```

Or construct with options:

```php
$options = new \Spameri\ElasticQuery\Options(
	size: 20,
	from: 0,
	sort: null,
	minScore: 1.0,
	includeVersion: true,
	scroll: '5m',
	scrollId: null,
);

$query = new \Spameri\ElasticQuery\ElasticQuery(options: $options);
```

---

## Pagination

##### Size and From (Offset)

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Set page size (number of results to return)
$query->options()->changeSize(20);

// Set offset (skip first N results)
$query->options()->changeFrom(40); // Page 3 with 20 per page

// Produces: { "size": 20, "from": 40, ... }
```

##### Minimum Score

Filter out results below a minimum relevance score:

```php
$options = new \Spameri\ElasticQuery\Options(
	size: 20,
	from: 0,
	minScore: 0.5, // Only return documents with score >= 0.5
);
```

##### Include Version

Include document version in results:

```php
$options = new \Spameri\ElasticQuery\Options(
	includeVersion: true,
);
```

---

## Scroll API

For iterating over large result sets:

```php
$options = new \Spameri\ElasticQuery\Options(
	size: 1000,
);

// Start scrolling (keep context alive for 5 minutes)
$options->startScroll('5m');

// After first request, store the scroll ID
$options->scrollInitialized($scrollIdFromResponse);

// Use scroll() and scrollId() getters
$scroll = $options->scroll();     // '5m'
$scrollId = $options->scrollId(); // The scroll context ID
```

---

## Sorting

##### Sort Class

Sort results by field values.
- Class: `\Spameri\ElasticQuery\Options\Sort`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Options/Sort.php)

```php
use Spameri\ElasticQuery\Options\Sort;

new Sort(
	field: 'created_at',
	type: Sort::DESC,              // ASC or DESC
	missing: Sort::MISSING_LAST,   // MISSING_LAST or MISSING_FIRST
);
```

Constants:
- `Sort::ASC` - Ascending order
- `Sort::DESC` - Descending order
- `Sort::MISSING_LAST` - Documents with missing field sorted last
- `Sort::MISSING_FIRST` - Documents with missing field sorted first

##### Adding Sorts to Query

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Sort by date descending
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\Sort('date', Sort::DESC)
);

// Then by score ascending
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\Sort('_score', Sort::ASC)
);
```

##### GeoDistanceSort

Sort by distance from a geographic point.
- Class: `\Spameri\ElasticQuery\Options\GeoDistanceSort`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html#geo-sorting)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Options/GeoDistanceSort.php)

```php
use Spameri\ElasticQuery\Options\GeoDistanceSort;
use Spameri\ElasticQuery\Options\Sort;

new GeoDistanceSort(
	field: 'location',           // Geo-point field
	lat: 40.7128,                // Latitude
	lon: -74.0060,               // Longitude
	type: Sort::ASC,             // ASC (nearest first) or DESC
	unit: 'km',                  // Distance unit: km, m, mi, etc.
	mode: 'min',                 // min, max, avg, median (for arrays)
	distanceType: 'arc',         // arc (accurate) or plane (faster)
);
```

---

## SortCollection

The sort collection manages multiple sort criteria.
- Class: `\Spameri\ElasticQuery\Options\SortCollection`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Options/SortCollection.php)

```php
// Access via options
$sortCollection = $query->options()->sort();

// Add sorts
$sortCollection->add(new Sort('date', Sort::DESC));
$sortCollection->add(new Sort('title', Sort::ASC));

// Check if sort exists
$sortCollection->isValue('date'); // true

// Get sort by field name
$dateSort = $sortCollection->get('date');

// Remove sort
$sortCollection->remove('date');

// Count sorts
$sortCollection->count();

// Clear all sorts
$sortCollection->clear();
```

---

## Complete Example

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Add search query
$query->addMustQuery(
	new \Spameri\ElasticQuery\Query\ElasticMatch('content', 'elasticsearch')
);

// Pagination: Page 2 with 25 results per page
$query->options()->changeSize(25);
$query->options()->changeFrom(25);

// Primary sort by relevance score
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\Sort('_score', Sort::DESC)
);

// Secondary sort by date for equal scores
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\Sort('created_at', Sort::DESC, Sort::MISSING_LAST)
);

// Execute query
$body = $query->toArray();
/*
{
    "size": 25,
    "from": 25,
    "sort": [
        { "_score": { "order": "DESC", "missing": "_last" } },
        { "created_at": { "order": "DESC", "missing": "_last" } }
    ],
    "query": { ... }
}
*/
```

## Geo-Distance Sorting Example

```php
$query = new \Spameri\ElasticQuery\ElasticQuery();

// Find restaurants
$query->addFilter(
	new \Spameri\ElasticQuery\Query\Term('type', 'restaurant')
);

// Sort by distance from user's location
$query->options()->sort()->add(
	new \Spameri\ElasticQuery\Options\GeoDistanceSort(
		field: 'location',
		lat: 51.5074,   // London coordinates
		lon: -0.1278,
		type: Sort::ASC,
		unit: 'km',
	)
);

$query->options()->changeSize(10);
```

# Highlight & Function Score

These features enhance search results with highlighting and custom scoring.

---

## Highlight

Highlight matching text fragments in search results.
- Class: `\Spameri\ElasticQuery\Highlight`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/highlighting.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Highlight.php)

### Basic Usage

```php
$highlight = new \Spameri\ElasticQuery\Highlight(
	preTags: ['<em>', '<strong>'],   // Tags before highlighted text
	postTags: ['</em>', '</strong>'], // Tags after highlighted text
	fields: ['title', 'content'],     // Fields to highlight
);

$query = new \Spameri\ElasticQuery\ElasticQuery(
	highlight: $highlight,
);
```

### Constructor Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `preTags` | `array` | HTML tags inserted before highlighted text |
| `postTags` | `array` | HTML tags inserted after highlighted text |
| `fields` | `array` | Field names to apply highlighting to |

### Accessing Highlights in Results

```php
$result = \Spameri\ElasticQuery\Response\ResultMapper::mapSearchResults($response);

foreach ($result->hits() as $hit) {
	$highlights = $hit->highlight(); // array|null

	if ($highlights !== null) {
		foreach ($highlights as $field => $fragments) {
			echo "{$field}: " . implode(' ... ', $fragments) . "\n";
		}
	}
}
```

### Example Output

```php
// Query for "elasticsearch"
// With highlight: <em> tags

// Result highlight array:
[
	'title' => ['Introduction to <em>Elasticsearch</em>'],
	'content' => [
		'<em>Elasticsearch</em> is a distributed search engine.',
		'Learn how to use <em>Elasticsearch</em> for full-text search.',
	],
]
```

---

## Function Score

Customize document scoring with mathematical functions.
- Class: `\Spameri\ElasticQuery\FunctionScore`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/FunctionScore.php)

### Basic Usage

```php
$functionScore = new \Spameri\ElasticQuery\FunctionScore(
	function: null, // Will be populated via function()
	scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MULTIPLY,
);

$query = new \Spameri\ElasticQuery\ElasticQuery(
	functionScore: $functionScore,
);
```

### Score Modes

How to combine scores from multiple functions:

```php
use Spameri\ElasticQuery\FunctionScore;

FunctionScore::SCORE_MODE_MULTIPLY // Multiply function scores (default)
FunctionScore::SCORE_MODE_SUM      // Add function scores
FunctionScore::SCORE_MODE_AVG      // Average of function scores
FunctionScore::SCORE_MODE_FIRST    // Use first matching function's score
FunctionScore::SCORE_MODE_MAX      // Use maximum function score
FunctionScore::SCORE_MODE_MIN      // Use minimum function score
```

### Adding Score Functions

```php
$functionScore = new \Spameri\ElasticQuery\FunctionScore(
	scoreMode: FunctionScore::SCORE_MODE_SUM,
);

// Add score functions
$functionScore->function()->add(
	new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
		field: 'popularity',
		factor: 1.2,
		modifier: 'log1p',
	)
);

$query = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
$query->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('title', 'search'));
```

---

## Score Functions

### FieldValueFactor

Modify score based on a numeric field value.
- Class: `\Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-field-value-factor)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/FunctionScore/ScoreFunction/FieldValueFactor.php)

```php
new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
	field: 'popularity',    // Numeric field to use
	factor: 1.2,            // Multiply field value by this
	modifier: 'log1p',      // Mathematical modifier
	missing: 1.0,           // Default if field is missing
);
```

#### Modifiers

| Modifier | Description | Formula |
|----------|-------------|---------|
| `none` | No modification | `field * factor` |
| `log` | Logarithm | `log(field * factor)` |
| `log1p` | Log + 1 (avoids log(0)) | `log(1 + field * factor)` |
| `log2p` | Log + 2 | `log(2 + field * factor)` |
| `ln` | Natural log | `ln(field * factor)` |
| `ln1p` | Natural log + 1 | `ln(1 + field * factor)` |
| `ln2p` | Natural log + 2 | `ln(2 + field * factor)` |
| `square` | Square | `(field * factor)²` |
| `sqrt` | Square root | `√(field * factor)` |
| `reciprocal` | Reciprocal | `1 / (field * factor)` |

```php
use Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor;

FieldValueFactor::MODIFIER_NONE
FieldValueFactor::MODIFIER_LOG
FieldValueFactor::MODIFIER_LOG1P
FieldValueFactor::MODIFIER_LOG2P
FieldValueFactor::MODIFIER_LN
FieldValueFactor::MODIFIER_LN1P
FieldValueFactor::MODIFIER_LN2P
FieldValueFactor::MODIFIER_SQUARE
FieldValueFactor::MODIFIER_SQRT
FieldValueFactor::MODIFIER_RECIPROCAL
```

### Weight

Apply a constant weight when a filter matches.
- Class: `\Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-weight)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/FunctionScore/ScoreFunction/Weight.php)

```php
// Boost featured products by 2x
new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(
	weight: 2.0,
	leafQuery: new \Spameri\ElasticQuery\Query\Term('featured', true),
);
```

### RandomScore

Add randomness to scoring (useful for sampling or A/B testing).
- Class: `\Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore`
- [Documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-random)
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/FunctionScore/ScoreFunction/RandomScore.php)

```php
// Random ordering (changes each request)
new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore();

// Consistent random ordering per user (same seed = same order)
new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore(
	seed: $userId, // User ID as seed for consistent personalization
);
```

---

## Complete Examples

### Highlight Search Results

```php
$query = new \Spameri\ElasticQuery\ElasticQuery(
	highlight: new \Spameri\ElasticQuery\Highlight(
		preTags: ['<mark>'],
		postTags: ['</mark>'],
		fields: ['title', 'content', 'description'],
	),
);

$query->addMustQuery(
	new \Spameri\ElasticQuery\Query\MultiMatch(
		fields: ['title^2', 'content'],
		query: 'elasticsearch tutorial',
	)
);
```

### Boost by Popularity and Recency

```php
$functionScore = new \Spameri\ElasticQuery\FunctionScore(
	scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_SUM,
);

// Boost by view count (logarithmic to prevent domination)
$functionScore->function()->add(
	new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\FieldValueFactor(
		field: 'view_count',
		factor: 0.1,
		modifier: 'log1p',
		missing: 1.0,
	)
);

// Boost featured items
$functionScore->function()->add(
	new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\Weight(
		weight: 10.0,
		leafQuery: new \Spameri\ElasticQuery\Query\Term('featured', true),
	)
);

$query = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
$query->addMustQuery(new \Spameri\ElasticQuery\Query\ElasticMatch('content', 'search'));
```

### Random Shuffle with Consistent User Experience

```php
$functionScore = new \Spameri\ElasticQuery\FunctionScore(
	scoreMode: \Spameri\ElasticQuery\FunctionScore::SCORE_MODE_MULTIPLY,
);

// Same user sees same order, different users see different orders
$functionScore->function()->add(
	new \Spameri\ElasticQuery\FunctionScore\ScoreFunction\RandomScore(
		seed: $currentUserId,
	)
);

$query = new \Spameri\ElasticQuery\ElasticQuery(functionScore: $functionScore);
$query->addFilter(new \Spameri\ElasticQuery\Query\Term('status', 'active'));
```

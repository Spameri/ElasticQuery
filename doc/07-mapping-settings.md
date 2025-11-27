# Mapping & Settings

Define Elasticsearch index mappings, analyzers, tokenizers, and filters.

---

## Settings

The `Settings` class is the main entry point for defining index configuration.
- Class: `\Spameri\ElasticQuery\Mapping\Settings`
- [Implementation](https://github.com/Spameri/ElasticQuery/blob/master/src/Mapping/Settings.php)

```php
$settings = new \Spameri\ElasticQuery\Mapping\Settings(
	indexName: 'my_index',
	hasSti: false,
	analysis: null,  // Will be auto-created
	mapping: null,   // Will be auto-created
	alias: null,     // Will be auto-created
);
```

### Adding Fields

```php
// Simple field types (convenience methods)
$settings->addMappingFieldKeyword('status');
$settings->addMappingFieldInteger('count');
$settings->addMappingFieldFloat('price');
$settings->addMappingFieldBoolean('active');

// Custom field with analyzer
$settings->addMappingField(
	new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
		name: 'title',
		type: \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		analyzer: new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(),
	)
);
```

### Adding Analyzers

```php
// Add built-in analyzer
$settings->addAnalyzer(new \Spameri\ElasticQuery\Mapping\Analyzer\Standard());

// Custom analyzers automatically register their filters
$settings->addAnalyzer($customAnalyzer);
```

### Full Example

```php
$settings = new \Spameri\ElasticQuery\Mapping\Settings('products');

// Add fields
$settings->addMappingFieldKeyword('sku');
$settings->addMappingFieldKeyword('category');
$settings->addMappingFieldFloat('price');
$settings->addMappingFieldInteger('stock');

// Add text field with custom analyzer
$settings->addMappingField(
	new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
		name: 'description',
		type: \Spameri\ElasticQuery\Mapping\AllowedValues::TYPE_TEXT,
		analyzer: new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(),
	)
);

// Convert to array for index creation
$indexSettings = $settings->toArray();
```

---

## Field Types

All field types are defined in `\Spameri\ElasticQuery\Mapping\AllowedValues`:

### Text Types
```php
AllowedValues::TYPE_TEXT     // Full-text searchable
AllowedValues::TYPE_KEYWORD  // Exact value matching
```

### Numeric Types
```php
AllowedValues::TYPE_INTEGER
AllowedValues::TYPE_LONG
AllowedValues::TYPE_SHORT
AllowedValues::TYPE_BYTE
AllowedValues::TYPE_DOUBLE
AllowedValues::TYPE_FLOAT
AllowedValues::TYPE_HALF_FLOAT
AllowedValues::TYPE_SCALED_FLOAT
```

### Date Types
```php
AllowedValues::TYPE_DATE
```

### Range Types
```php
AllowedValues::TYPE_INTEGER_RANGE
AllowedValues::TYPE_FLOAT_RANGE
AllowedValues::TYPE_LONG_RANGE
AllowedValues::TYPE_DOUBLE_RANGE
AllowedValues::TYPE_DATE_RANGE
```

### Complex Types
```php
AllowedValues::TYPE_OBJECT   // JSON object
AllowedValues::TYPE_NESTED   // Nested documents (queryable separately)
```

### Geo Types
```php
AllowedValues::TYPE_GEO_POINT  // Lat/lon point
AllowedValues::TYPE_GEO_SHAPE  // Arbitrary geo shapes
```

### Special Types
```php
AllowedValues::TYPE_IP          // IPv4/IPv6 addresses
AllowedValues::TYPE_COMPLETION  // Autocomplete suggestions
AllowedValues::TYPE_BOOLEAN
AllowedValues::TYPE_TOKEN_COUNT
AllowedValues::TYPE_PERCOLATOR
AllowedValues::TYPE_JOIN
AllowedValues::TYPE_ALIAS
```

---

## Analyzers

Built-in analyzers in `src/Mapping/Analyzer/`:

### Standard Analyzer
Default analyzer with grammar-based tokenization.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Standard();
```

### Simple Analyzer
Divides text on non-letter characters.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Simple();
```

### Whitespace Analyzer
Divides text on whitespace only.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Whitespace();
```

### Keyword Analyzer
Treats entire input as a single token.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Keyword();
```

### Stop Analyzer
Like simple but removes stop words.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Stop();
```

### Pattern Analyzer
Uses regex to split text.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Pattern();
```

### Fingerprint Analyzer
Creates fingerprint for duplicate detection.
```php
new \Spameri\ElasticQuery\Mapping\Analyzer\Fingerprint();
```

### Language-Specific Analyzers
Custom dictionary analyzers available in `src/Mapping/Analyzer/Custom/` for 30+ languages:

- Arabic, Armenian, Basque, Bengali, Brazilian, Bulgarian
- Catalan, CJK, Czech, Danish, Dutch, English
- Finnish, French, Galician, German, Greek
- Hindi, Hungarian, Indonesian, Irish, Italian
- Latvian, Lithuanian, Norwegian, Persian, Portuguese
- Romanian, Russian, Sorani, Spanish, Swedish
- Turkish, Thai

---

## Tokenizers

Available in `src/Mapping/Tokenizer/`:

### Word-Oriented Tokenizers
```php
new \Spameri\ElasticQuery\Mapping\Tokenizer\Standard();   // Grammar-based
new \Spameri\ElasticQuery\Mapping\Tokenizer\Letter();     // On non-letters
new \Spameri\ElasticQuery\Mapping\Tokenizer\Lowercase();  // Letter + lowercase
new \Spameri\ElasticQuery\Mapping\Tokenizer\Whitespace(); // On whitespace
new \Spameri\ElasticQuery\Mapping\Tokenizer\UaxUrlEmail(); // Preserves URLs/emails
new \Spameri\ElasticQuery\Mapping\Tokenizer\Classic();    // Grammar-based (legacy)
new \Spameri\ElasticQuery\Mapping\Tokenizer\Thai();       // Thai text
```

### Partial Word Tokenizers
```php
new \Spameri\ElasticQuery\Mapping\Tokenizer\NGram();      // N-gram tokens
new \Spameri\ElasticQuery\Mapping\Tokenizer\EdgeNGram(); // Edge n-grams (autocomplete)
```

### Structured Text Tokenizers
```php
new \Spameri\ElasticQuery\Mapping\Tokenizer\Keyword();           // No tokenization
new \Spameri\ElasticQuery\Mapping\Tokenizer\Pattern();           // Regex-based
new \Spameri\ElasticQuery\Mapping\Tokenizer\SimplePattern();     // Simple regex
new \Spameri\ElasticQuery\Mapping\Tokenizer\SimplePatternSplit(); // Split on regex
new \Spameri\ElasticQuery\Mapping\Tokenizer\CharGroup();         // Split on chars
new \Spameri\ElasticQuery\Mapping\Tokenizer\Path();              // Path hierarchy
```

---

## Filters

Token filters transform tokens after tokenization. Available in `src/Mapping/Filter/`:

### Common Filters
- `Lowercase` - Convert to lowercase
- `Uppercase` - Convert to uppercase
- `Stemmer` - Language-specific stemming
- `EdgeNgram` - Create edge n-grams
- `Ngram` - Create n-grams
- `Shingle` - Create word n-grams
- `WordDelimiter` - Split on word boundaries
- `ASCIIFolding` - Convert to ASCII equivalents
- `Trim` - Remove whitespace
- `Length` - Filter by token length
- `Unique` - Remove duplicates
- `Stop` - Remove stop words
- `Synonym` - Apply synonyms

### Language-Specific Filters

#### Hunspell (Spell Check) Filters
Available for 24 languages in `src/Mapping/Filter/Hunspell/`:
- Czech, Danish, Dutch, English (AU/GB/US), French
- German, Hungarian, Italian, Norwegian, Polish
- Portuguese, Romanian, Russian, Slovak, Spanish
- Swedish, Turkish, Ukrainian

#### Stop Word Filters
Available for 27 languages in `src/Mapping/Filter/Stop/`:
- Arabic, Armenian, Basque, Bengali, Brazilian, Bulgarian
- Catalan, CJK, Czech, Danish, Dutch, English
- Finnish, French, Galician, German, Greek, Hindi
- Hungarian, Indonesian, Irish, Italian, Latvian, Lithuanian
- Norwegian, Persian, Portuguese, Romanian, Russian
- Sorani, Spanish, Swedish, Thai, Turkish

#### Synonym Filters
Available in `src/Mapping/Filter/Synonym/`:
- Czech, German, English (more can be added)

---

## Field Configuration Classes

### Field
Basic field definition.
```php
new \Spameri\ElasticQuery\Mapping\Settings\Mapping\Field(
	name: 'title',
	type: AllowedValues::TYPE_TEXT,
	analyzer: new \Spameri\ElasticQuery\Mapping\Analyzer\Standard(),
	fieldData: false, // Enable fielddata for aggregations on text
);
```

### FieldObject
Object type with nested properties.
```php
$fieldObject = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\FieldObject('address');
// Add properties to the object...
$settings->addMappingFieldObject($fieldObject);
```

### NestedObject
Nested document type (maintains array relationships).
```php
$nested = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\NestedObject('comments');
$settings->addMappingNestedObject($nested);
```

### SubFields
Multi-fields for different analysis on same data.
```php
$subFields = new \Spameri\ElasticQuery\Mapping\Settings\Mapping\SubFields(
	name: 'title',
	// Define sub-field variations...
);
$settings->addMappingSubField($subFields);
```

---

## Complete Example

```php
use Spameri\ElasticQuery\Mapping\Settings;
use Spameri\ElasticQuery\Mapping\AllowedValues;
use Spameri\ElasticQuery\Mapping\Settings\Mapping\Field;
use Spameri\ElasticQuery\Mapping\Analyzer\Standard;

// Create index settings
$settings = new Settings('blog_posts');

// Add simple fields
$settings->addMappingFieldKeyword('id');
$settings->addMappingFieldKeyword('slug');
$settings->addMappingFieldKeyword('status');
$settings->addMappingFieldKeyword('author_id');

// Add date field
$settings->addMappingField(
	new Field('published_at', AllowedValues::TYPE_DATE)
);

// Add numeric fields
$settings->addMappingFieldInteger('view_count');
$settings->addMappingFieldFloat('rating');
$settings->addMappingFieldBoolean('featured');

// Add text fields with analyzers
$settings->addMappingField(
	new Field(
		name: 'title',
		type: AllowedValues::TYPE_TEXT,
		analyzer: new Standard(),
	)
);

$settings->addMappingField(
	new Field(
		name: 'content',
		type: AllowedValues::TYPE_TEXT,
		analyzer: new Standard(),
	)
);

// Add geo field
$settings->addMappingField(
	new Field('location', AllowedValues::TYPE_GEO_POINT)
);

// Get the complete settings array
$indexConfig = $settings->toArray();

// Use with Elasticsearch client
$client->indices()->create([
	'index' => 'blog_posts',
	'body' => $indexConfig,
]);
```

---

## Analysis Configuration

Access the analysis settings directly:

```php
$settings = new \Spameri\ElasticQuery\Mapping\Settings('my_index');

// Access analysis components
$analysis = $settings->analysis();

// Add custom analyzer
$analysis->analyzer()->add($customAnalyzer);

// Add custom tokenizer
$analysis->tokenizer()->add($customTokenizer);

// Add custom filter
$analysis->filter()->add($customFilter);
```

### Custom Analyzer Example

Custom analyzers implement `CustomAnalyzerInterface` and automatically register their required filters:

```php
// When you add a custom analyzer, its filters are auto-registered
$settings->addAnalyzer($customAnalyzer);

// To remove an analyzer and its filters
$settings->removeAnalyzer('analyzer_name');
```

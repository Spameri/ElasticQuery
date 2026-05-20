# Changelog

## v2 — full DSL coverage

This branch brings every documented Elasticsearch DSL feature under typed PHP objects, fixes two queries that produced invalid DSL, and round-trips every feature against a real ES container via the new `AbstractElasticTestCase`.

### Test infrastructure

- New `tests/SpameriTests/ElasticQuery/AbstractElasticTestCase` base class with `createIndex($mapping)`, `indexDocument($body, $id, $refresh)`, `search($elasticQuery)`, `deleteIndex()`, and a generic `request($method, $path, $body)`. Tests that extend it shrink from ~70 lines of curl boilerplate to ~15.

### Bug fixes (BC-breaking)

| File | Previous | Fixed |
| --- | --- | --- |
| `Query/GeoDistance` | Emitted `{pin: {location: ...}}` (invalid DSL) and lacked the required `distance` argument. | Emits proper `geo_distance` envelope. Constructor now takes `distance` (required), plus `distance_type`, `validation_method`, `ignore_unmapped`, `boost`. |
| `Query/Nested` | Wrapped inner query in `[$queryArray]` (extra list level — rejected by ES). | Inner query is now an object. Added `score_mode`, `ignore_unmapped`, `inner_hits`. |
| `Query/PhrasePrefix` | `int $boost = 1` (inconsistent type). | `float $boost = 1.0`. |
| `Options/GeoDistanceSort` | `ignore_unmapped` hard-coded to `true`. | Constructor arg `bool $ignoreUnmapped = true`. |

### New query types

- **Knn** — vector similarity (field, queryVector, k, numCandidates, similarity, filter, boost).
- **SparseVector** — ELSER-style sparse vector query (inference_id+query or queryVector tokens).
- **TextExpansion** — legacy ELSER form (model_id, model_text).
- **Semantic** — queries a `semantic_text` field.
- **RuleQuery** — Search Application query rules over an organic query.
- **WeightedTokens** — token weights against a sparse_vector field.

### Existing queries — new constructor arguments

| Query | New args |
| --- | --- |
| `ElasticMatch` | `zero_terms_query`, `auto_generate_synonyms_phrase_query`, `lenient`, `prefix_length`, `max_expansions`, `fuzzy_transpositions`, `fuzzy_rewrite` |
| `MultiMatch` | `tie_breaker`, `slop`, `prefix_length`, `max_expansions`, `lenient`, `zero_terms_query`, `auto_generate_synonyms_phrase_query`, `fuzzy_transpositions`, `fuzzy_rewrite` |
| `MatchPhrase` | `zero_terms_query` |
| `PhrasePrefix` | `analyzer`, `max_expansions`, `zero_terms_query` |
| `MatchBoolPrefix` | `fuzziness`, `prefix_length`, `max_expansions`, `fuzzy_transpositions`, `fuzzy_rewrite` |
| `QueryString` | `analyze_wildcard`, `auto_generate_synonyms_phrase_query`, `enable_position_increments`, `fuzziness`, `fuzzy_max_expansions`, `fuzzy_prefix_length`, `fuzzy_transpositions`, `lenient`, `max_determinized_states`, `minimum_should_match`, `quote_analyzer`, `phrase_slop`, `quote_field_suffix`, `rewrite`, `time_zone`, `type`, `tie_breaker` |
| `SimpleQueryString` | `analyze_wildcard`, `auto_generate_synonyms_phrase_query`, `fuzzy_max_expansions`, `fuzzy_prefix_length`, `fuzzy_transpositions`, `lenient`, `minimum_should_match`, `quote_field_suffix` |
| `CombinedFields` | `auto_generate_synonyms_phrase_query` |
| `Term` | `case_insensitive` |
| `Terms` | accepts `TermsLookup` for cross-document terms resolution |
| `Range` | `gt`, `lt`, `format`, `relation` (new `Range\Relation` constants), `time_zone` |
| `Exists` | `boost` |
| `WildCard` | `case_insensitive`, `rewrite` |
| `Prefix` | `rewrite` |
| `Fuzzy` | `transpositions`, `rewrite` |
| `Regexp` | `rewrite` |
| `TermSet` | `boost` |
| `HasChild` | `inner_hits` |
| `HasParent` | `inner_hits` |
| `Nested` | `score_mode`, `ignore_unmapped`, `inner_hits` |
| `ParentId` | `boost` |
| `GeoBoundingBox` | `validation_method`, `ignore_unmapped`, `boost` |
| `GeoShape` | `indexed_shape` (new `IndexedShape` sub-object), `boost` |
| `Shape` | `indexed_shape`, `boost` |
| `MoreLikeThis` | `boost_terms`, `include`, `min_doc_freq`, `max_doc_freq`, `min_word_length`, `max_word_length`, `stop_words`, `analyzer`, `boost`, `fail_on_unsupported_field` |
| `Percolate` | `documents` (multi-doc), `name`, `routing`, `preference`, `version` |

### New sub-objects

- `Query/TermsLookup` — `index`, `id`, `path`, `routing`.
- `Query/Range/Relation` — constants: `INTERSECTS`, `CONTAINS`, `WITHIN`.
- `Query/InnerHits` — `name`, `from`, `size`, `sort`, `_source`, `highlight`, `explain`, `script_fields`, `docvalue_fields`, `version`, `seq_no_primary_term`, `stored_fields`, `track_scores`.
- `Query/IndexedShape` — `id`, `index`, `path`, `routing`.
- `Script` (top-level) — reusable script value object (`source`, `lang`, `params`).

### Aggregations — new types

Bucket: `Filters` (named filters), `AutoDateHistogram`, `VariableWidthHistogram`, `CategorizeText` *(platinum license)*, `FrequentItemSets` *(platinum license)*, `IpPrefix`, `TimeSeries`.

Metric: `TopMetrics`, `GeoLine` *(gold license)*, `TTest`, `Rate`, `MatrixStats`.

Pipeline/sampler/ML: `RandomSampler`, `CumulativeCardinality`, `ExtendedStatsBucket`, `Inference`.

### Aggregations — new constructor arguments

| Agg | New args |
| --- | --- |
| `Min`/`Max`/`Avg`/`Sum`/`ValueCount`/`Stats` | `missing`, `script`, `format` |
| `ExtendedStats` | `missing`, `script`, `format` (kept `sigma`) |
| `Cardinality` | `script`, `missing`, `rehash` |
| `MedianAbsoluteDeviation`/`StringStats` | `missing`, `script` |
| `BoxPlot` | `missing`, `script`, `execution_hint` |
| `Percentiles` | `tdigest`, `hdr`, `missing`, `script` |
| `PercentileRanks` | `hdr`, `missing`, `script` |
| `WeightedAvg` | **rewritten** — takes typed `WeightedAvgValue` for value/weight (each with `field`/`script`/`missing`), plus `format` |
| `TopHits` | **rewritten** — `from`, `sort`, `_source`, `highlight`, `explain`, `script_fields`, `docvalue_fields`, `version`, `seq_no_primary_term`, `stored_fields`, `track_scores` |
| `Term` | `min_doc_count`, `shard_size`, `shard_min_doc_count`, `show_term_doc_count_error`, `script`, `collect_mode`, `execution_hint`, `value_type`, `format`; `include`/`exclude` accept arrays |
| `MultiTerms` | `order`, `min_doc_count`, `shard_size`, `shard_min_doc_count`, `collect_mode`, `format` |
| `RareTerms` | `include`, `exclude`, `missing` |
| `SignificantTerms` | `shard_size`, `shard_min_doc_count`, `execution_hint`, `background_filter`, `heuristic` (with `HEURISTIC_*` constants) |
| `SignificantText` | `shard_size`, `shard_min_doc_count`, `min_doc_count`, `background_filter`, `source_fields` |
| `Range` | `script`, `missing`, `format` |
| `DateRange` | `script`, `missing` |
| `Histogram` | `min_doc_count`, `extended_bounds` (new `Histogram\Bounds`), `hard_bounds`, `offset`, `order`, `script`, `missing`, `keyed`, `format` |
| `DateHistogram` | `extended_bounds`, `hard_bounds`, `keyed`, `order`, `script`, `missing` |
| `IpRange` | **rewritten** — new `IpRange\IpRangeValue` with `mask` (CIDR) support |
| `Filter` | **rewritten** — accepts any `LeafQueryInterface` directly |
| `Composite` | typed sources: `Composite\TermsSource`, `Composite\HistogramSource`, `Composite\DateHistogramSource`, `Composite\GeotileGridSource`, each with `order`/`missing_bucket` |
| `AdjacencyMatrix` | `separator`, accepts `LeafQueryInterface` for filters |
| `GeoDistance` (agg) | `keyed`, `script`, `missing` |
| `GeoHashGrid`/`GeoTileGrid` | `bounds` |
| `DiversifiedSampler` | `execution_hint`, `script` |
| `Missing` | `script` |

### Score functions

- New `FunctionScore/ScoreFunction/Decay/Gauss`, `Linear`, `Exp` with shared `AbstractDecay` parent (`field`, `origin`, `scale`, `offset`, `decay`, `multi_value_mode`).
- New `FunctionScore/ScoreFunction/ScriptScore` (function variant — distinct from the `Query/ScriptScore` leaf).
- `FunctionScore` gained `boost`, `boost_mode` (with `BOOST_MODE_*` constants), `max_boost`, `min_score`.

### Sort

- `Sort` gains `mode`, `nested` (new `NestedSort`), `numeric_type`, `unmapped_type`, `format`.
- New `Options/ScriptSort` — script-based sort.
- New `Options/NestedSort` — path/filter/max_children for nested sorting (recursive).

### Highlight — rewritten

- `Highlight/HighlightField` — per-field config (type, number_of_fragments, fragment_size, all boundary_*, encoder, force_source, fragmenter, highlight_query, matched_fields, no_match_size, order, phrase_limit, require_field_match, tags_schema, pre_tags, post_tags).
- `Highlight/HighlightFieldCollection` — typed collection.
- `Highlight` accepts either `HighlightFieldCollection` or simple `array<string>` of field names (BC). Adds all global options.

### Options — many new fields

| Field | Type |
| --- | --- |
| `_source` | new `Options\Source` (includes/excludes, or `false`) |
| `track_total_hits` | `bool\|int` |
| `track_scores` | `bool` |
| `explain` | `bool` |
| `terminate_after` | `int` |
| `timeout` | `string` |
| `search_after` | `array` |
| `pit` | new `Options\Pit` |
| `stored_fields` | `array` |
| `docvalue_fields` | `array` |
| `fields` | `array` |
| `script_fields` | `array` |
| `runtime_mappings` | `array` |
| `seq_no_primary_term` | `bool` |
| `indices_boost` | `array` |
| `collapse` | new `Options\Collapse` |
| `rescore` | `array<Options\Rescore>` |
| `suggesters` | `array<Suggest\SuggesterInterface>` |
| `profile` | `bool` |
| `stats` | `array<string>` |
| `ext` | `array` |

`ElasticQuery::toArray()` wires `collapse`, `rescore`, and `suggest` to the top-level request body.

### Filter container — bool expansion

`Filter/FilterCollection` previously exposed only `must()`. It now mirrors `Query/QueryCollection` with `must()`, `should()`, `mustNot()`, and `filter()` — the `bool` body emits all four arms.

### Suggesters

- `Options/Suggest/SuggesterInterface`
- `Options/Suggest/TermSuggester`
- `Options/Suggest/PhraseSuggester`
- `Options/Suggest/CompletionSuggester`

### Response mapper

- `ResultMapper` now handles named buckets (string keys, e.g. from `Filters` agg) and composite-key buckets (array keys).
- `Result/Aggregation/Bucket.from`/`to` accept `string` (e.g. for IP / date range buckets).

### CI / tests

- 218 tests, 3 skipped on basic license (geo_line, categorize_text).
- ES 9.2.2 container in CI; `make tests` passes end-to-end against it.
- The two pre-existing buggy tests for `GeoDistance` and `Nested` (which asserted invalid output) are now corrected and re-run as integration tests against ES.

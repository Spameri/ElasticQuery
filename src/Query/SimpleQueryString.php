<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-simple-query-string-query.html
 */
class SimpleQueryString implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $fields
	 */
	public function __construct(
		private string $query,
		private array $fields = [],
		private string|null $defaultOperator = null,
		private string|null $analyzer = null,
		private string|null $flags = null,
		private float $boost = 1.0,
		private bool|null $analyzeWildcard = null,
		private bool|null $autoGenerateSynonymsPhraseQuery = null,
		private int|null $fuzzyMaxExpansions = null,
		private int|null $fuzzyPrefixLength = null,
		private bool|null $fuzzyTranspositions = null,
		private bool|null $lenient = null,
		private int|string|null $minimumShouldMatch = null,
		private string|null $quoteFieldSuffix = null,
	)
	{
	}


	public function key(): string
	{
		return 'simple_query_string_' . $this->query;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'query' => $this->query,
			'boost' => $this->boost,
		];

		if ($this->fields !== []) {
			$body['fields'] = $this->fields;
		}

		if ($this->defaultOperator !== null) {
			$body['default_operator'] = $this->defaultOperator;
		}

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->flags !== null) {
			$body['flags'] = $this->flags;
		}

		if ($this->analyzeWildcard !== null) {
			$body['analyze_wildcard'] = $this->analyzeWildcard;
		}

		if ($this->autoGenerateSynonymsPhraseQuery !== null) {
			$body['auto_generate_synonyms_phrase_query'] = $this->autoGenerateSynonymsPhraseQuery;
		}

		if ($this->fuzzyMaxExpansions !== null) {
			$body['fuzzy_max_expansions'] = $this->fuzzyMaxExpansions;
		}

		if ($this->fuzzyPrefixLength !== null) {
			$body['fuzzy_prefix_length'] = $this->fuzzyPrefixLength;
		}

		if ($this->fuzzyTranspositions !== null) {
			$body['fuzzy_transpositions'] = $this->fuzzyTranspositions;
		}

		if ($this->lenient !== null) {
			$body['lenient'] = $this->lenient;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->quoteFieldSuffix !== null) {
			$body['quote_field_suffix'] = $this->quoteFieldSuffix;
		}

		return [
			'simple_query_string' => $body,
		];
	}

}

<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html
 */
class QueryString implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $fields
	 */
	public function __construct(
		private string $query,
		private array $fields = [],
		private string|null $defaultField = null,
		private string|null $defaultOperator = null,
		private string|null $analyzer = null,
		private bool|null $allowLeadingWildcard = null,
		private float $boost = 1.0,
		private bool|null $analyzeWildcard = null,
		private bool|null $autoGenerateSynonymsPhraseQuery = null,
		private bool|null $enablePositionIncrements = null,
		private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = null,
		private int|null $fuzzyMaxExpansions = null,
		private int|null $fuzzyPrefixLength = null,
		private bool|null $fuzzyTranspositions = null,
		private bool|null $lenient = null,
		private int|null $maxDeterminizedStates = null,
		private int|string|null $minimumShouldMatch = null,
		private string|null $quoteAnalyzer = null,
		private int|null $phraseSlop = null,
		private string|null $quoteFieldSuffix = null,
		private string|null $rewrite = null,
		private string|null $timeZone = null,
		private string|null $type = null,
		private float|null $tieBreaker = null,
	)
	{
	}


	public function key(): string
	{
		return 'query_string_' . $this->query;
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

		if ($this->defaultField !== null) {
			$body['default_field'] = $this->defaultField;
		}

		if ($this->defaultOperator !== null) {
			$body['default_operator'] = $this->defaultOperator;
		}

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->allowLeadingWildcard !== null) {
			$body['allow_leading_wildcard'] = $this->allowLeadingWildcard;
		}

		if ($this->analyzeWildcard !== null) {
			$body['analyze_wildcard'] = $this->analyzeWildcard;
		}

		if ($this->autoGenerateSynonymsPhraseQuery !== null) {
			$body['auto_generate_synonyms_phrase_query'] = $this->autoGenerateSynonymsPhraseQuery;
		}

		if ($this->enablePositionIncrements !== null) {
			$body['enable_position_increments'] = $this->enablePositionIncrements;
		}

		if ($this->fuzziness !== null) {
			$body['fuzziness'] = $this->fuzziness->__toString();
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

		if ($this->maxDeterminizedStates !== null) {
			$body['max_determinized_states'] = $this->maxDeterminizedStates;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->quoteAnalyzer !== null) {
			$body['quote_analyzer'] = $this->quoteAnalyzer;
		}

		if ($this->phraseSlop !== null) {
			$body['phrase_slop'] = $this->phraseSlop;
		}

		if ($this->quoteFieldSuffix !== null) {
			$body['quote_field_suffix'] = $this->quoteFieldSuffix;
		}

		if ($this->rewrite !== null) {
			$body['rewrite'] = $this->rewrite;
		}

		if ($this->timeZone !== null) {
			$body['time_zone'] = $this->timeZone;
		}

		if ($this->type !== null) {
			$body['type'] = $this->type;
		}

		if ($this->tieBreaker !== null) {
			$body['tie_breaker'] = $this->tieBreaker;
		}

		return [
			'query_string' => $body,
		];
	}

}

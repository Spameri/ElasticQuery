<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
 */
class MultiMatch implements LeafQueryInterface
{

	/**
	 * @param array<int, string> $fields
	 */
	public function __construct(
		private array $fields,
		private bool|int|string|null $query,
		private float $boost = 1.0,
		private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = null,
		private string $type = \Spameri\ElasticQuery\Query\Match\MultiMatchType::BEST_FIELDS,
		private int|string|null $minimumShouldMatch = null,
		private string $operator = \Spameri\ElasticQuery\Query\Match\Operator::OR,
		private string|null $analyzer = null,
		private float|null $tieBreaker = null,
		private int|null $slop = null,
		private int|null $prefixLength = null,
		private int|null $maxExpansions = null,
		private bool|null $lenient = null,
		private string|null $zeroTermsQuery = null,
		private bool|null $autoGenerateSynonymsPhraseQuery = null,
		private bool|null $fuzzyTranspositions = null,
		private string|null $fuzzyRewrite = null,
	)
	{
		if ( ! \in_array($operator, \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $operator is invalid, see \Spameri\ElasticQuery\Query\Match\Operator::OPERATORS for valid arguments.',
			);
		}
		if ( ! \in_array($type, \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES, true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Parameter $type is invalid, see \Spameri\ElasticQuery\Query\Match\MultiMatchType::TYPES for valid arguments.',
			);
		}

	}


	public function changeAnalyzer(string $newAnalyzer): void
	{
		$this->analyzer = $newAnalyzer;
	}


	public function key(): string
	{
		return 'multiMatch_' . \implode('-', $this->fields) . '_' . (string) $this->query;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'query' => $this->query,
			'type' => $this->type,
			'fields' => $this->fields,
			'boost' => $this->boost,
			'operator' => $this->operator,
		];

		if ($this->fuzziness !== null && $this->fuzziness->__toString() !== '') {
			$body['fuzziness'] = $this->fuzziness->__toString();
		}

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->tieBreaker !== null) {
			$body['tie_breaker'] = $this->tieBreaker;
		}

		if ($this->slop !== null) {
			$body['slop'] = $this->slop;
		}

		if ($this->prefixLength !== null) {
			$body['prefix_length'] = $this->prefixLength;
		}

		if ($this->maxExpansions !== null) {
			$body['max_expansions'] = $this->maxExpansions;
		}

		if ($this->lenient !== null) {
			$body['lenient'] = $this->lenient;
		}

		if ($this->zeroTermsQuery !== null) {
			$body['zero_terms_query'] = $this->zeroTermsQuery;
		}

		if ($this->autoGenerateSynonymsPhraseQuery !== null) {
			$body['auto_generate_synonyms_phrase_query'] = $this->autoGenerateSynonymsPhraseQuery;
		}

		if ($this->fuzzyTranspositions !== null) {
			$body['fuzzy_transpositions'] = $this->fuzzyTranspositions;
		}

		if ($this->fuzzyRewrite !== null) {
			$body['fuzzy_rewrite'] = $this->fuzzyRewrite;
		}

		return [
			'multi_match' => $body,
		];
	}

}

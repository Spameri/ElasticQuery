<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-bool-prefix-query.html
 */
class MatchBoolPrefix implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
		private string|null $operator = null,
		private int|string|null $minimumShouldMatch = null,
		private string|null $analyzer = null,
		private \Spameri\ElasticQuery\Query\Match\Fuzziness|null $fuzziness = null,
		private int|null $prefixLength = null,
		private int|null $maxExpansions = null,
		private bool|null $fuzzyTranspositions = null,
		private string|null $fuzzyRewrite = null,
	)
	{
	}


	public function key(): string
	{
		return 'match_bool_prefix_' . $this->field . '_' . $this->query;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'query' => $this->query,
			'boost' => $this->boost,
		];

		if ($this->operator !== null) {
			$body['operator'] = $this->operator;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->fuzziness !== null) {
			$body['fuzziness'] = $this->fuzziness->__toString();
		}

		if ($this->prefixLength !== null) {
			$body['prefix_length'] = $this->prefixLength;
		}

		if ($this->maxExpansions !== null) {
			$body['max_expansions'] = $this->maxExpansions;
		}

		if ($this->fuzzyTranspositions !== null) {
			$body['fuzzy_transpositions'] = $this->fuzzyTranspositions;
		}

		if ($this->fuzzyRewrite !== null) {
			$body['fuzzy_rewrite'] = $this->fuzzyRewrite;
		}

		return [
			'match_bool_prefix' => [
				$this->field => $body,
			],
		];
	}

}

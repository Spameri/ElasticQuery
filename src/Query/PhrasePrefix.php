<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase-prefix.html
 */
class PhrasePrefix implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $queryString,
		private float $boost = 1.0,
		private int $slop = 1,
		private string|null $analyzer = null,
		private int|null $maxExpansions = null,
		private string|null $zeroTermsQuery = null,
	)
	{
	}


	public function key(): string
	{
		return 'phrase_prefix_' . $this->field . '_' . $this->queryString;
	}


	/**
	 * @return array<string, array<string, array<string, mixed>>>
	 */
	public function toArray(): array
	{
		$body = [
			'query' => $this->queryString,
			'boost' => $this->boost,
			'slop' => $this->slop,
		];

		if ($this->analyzer !== null) {
			$body['analyzer'] = $this->analyzer;
		}

		if ($this->maxExpansions !== null) {
			$body['max_expansions'] = $this->maxExpansions;
		}

		if ($this->zeroTermsQuery !== null) {
			$body['zero_terms_query'] = $this->zeroTermsQuery;
		}

		return [
			'match_phrase_prefix' => [
				$this->field => $body,
			],
		];
	}

}

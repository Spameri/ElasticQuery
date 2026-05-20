<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-combined-fields-query.html
 */
class CombinedFields implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $fields
	 */
	public function __construct(
		private array $fields,
		private string $query,
		private float $boost = 1.0,
		private string|null $operator = null,
		private int|string|null $minimumShouldMatch = null,
		private string|null $zeroTermsQuery = null,
		private bool|null $autoGenerateSynonymsPhraseQuery = null,
	)
	{
		if ($fields === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'CombinedFields query requires at least one field.',
			);
		}
	}


	public function key(): string
	{
		return 'combined_fields_' . \implode('-', $this->fields) . '_' . $this->query;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'query' => $this->query,
			'fields' => $this->fields,
			'boost' => $this->boost,
		];

		if ($this->operator !== null) {
			$body['operator'] = $this->operator;
		}

		if ($this->minimumShouldMatch !== null) {
			$body['minimum_should_match'] = $this->minimumShouldMatch;
		}

		if ($this->zeroTermsQuery !== null) {
			$body['zero_terms_query'] = $this->zeroTermsQuery;
		}

		if ($this->autoGenerateSynonymsPhraseQuery !== null) {
			$body['auto_generate_synonyms_phrase_query'] = $this->autoGenerateSynonymsPhraseQuery;
		}

		return [
			'combined_fields' => $body,
		];
	}

}

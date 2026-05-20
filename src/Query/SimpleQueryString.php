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

		return [
			'simple_query_string' => $body,
		];
	}

}

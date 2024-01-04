<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html
 */
class WildCard implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private string $query,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'wildcard_' . $this->field . '_' . $this->query;
	}


	public function toArray(): array
	{
		return [
			'wildcard' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
				],
			],
		];
	}

}

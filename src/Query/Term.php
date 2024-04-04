<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class Term implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private float|bool|int|string $query,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'term_' . $this->field . '_' . $this->query;
	}


	public function toArray(): array
	{
		return [
			'term' => [
				$this->field => [
					'value' => $this->query,
					'boost' => $this->boost,
				],
			],
		];
	}

}

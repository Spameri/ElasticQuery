<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-ids-query.html
 */
class Ids implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, string> $values
	 */
	public function __construct(
		private array $values,
		private float $boost = 1.0,
	)
	{
		if ($values === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Ids query must contain at least one id.',
			);
		}
	}


	public function key(): string
	{
		return 'ids_' . \implode('-', $this->values);
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'ids' => [
				'values' => $this->values,
				'boost' => $this->boost,
			],
		];
	}

}

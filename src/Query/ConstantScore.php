<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-constant-score-query.html
 */
class ConstantScore implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $filter,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'constant_score_' . $this->filter->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'constant_score' => [
				'filter' => $this->filter->toArray(),
				'boost' => $this->boost,
			],
		];
	}

}

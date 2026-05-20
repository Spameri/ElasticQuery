<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-boosting-query.html
 */
class Boosting implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $positive,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface $negative,
		private float $negativeBoost,
	)
	{
	}


	public function key(): string
	{
		return 'boosting_' . $this->positive->key() . '_' . $this->negative->key();
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'boosting' => [
				'positive' => $this->positive->toArray(),
				'negative' => $this->negative->toArray(),
				'negative_boost' => $this->negativeBoost,
			],
		];
	}

}

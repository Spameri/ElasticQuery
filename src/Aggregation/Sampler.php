<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-sampler-aggregation.html
 */
class Sampler implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private int $shardSize,
		private string $key = 'sampler',
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, int>>
	 */
	public function toArray(): array
	{
		return [
			'sampler' => [
				'shard_size' => $this->shardSize,
			],
		];
	}

}

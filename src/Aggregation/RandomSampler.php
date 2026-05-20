<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-random-sampler-aggregation.html
 */
class RandomSampler implements LeafAggregationInterface
{

	public function __construct(
		private float $probability,
		private int|null $seed = null,
		private int|null $shardSeed = null,
		private string $key = 'random_sampler',
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['probability' => $this->probability];

		if ($this->seed !== null) {
			$array['seed'] = $this->seed;
		}

		if ($this->shardSeed !== null) {
			$array['shard_seed'] = $this->shardSeed;
		}

		return ['random_sampler' => $array];
	}

}

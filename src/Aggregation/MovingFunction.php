<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-movfn-aggregation.html
 */
class MovingFunction implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $bucketsPath,
		private int $window,
		private string $script,
		private int|null $shift = null,
		private string|null $gapPolicy = null,
		private string $key = 'moving_fn',
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
		$array = [
			'buckets_path' => $this->bucketsPath,
			'window' => $this->window,
			'script' => $this->script,
		];

		if ($this->shift !== null) {
			$array['shift'] = $this->shift;
		}

		if ($this->gapPolicy !== null) {
			$array['gap_policy'] = $this->gapPolicy;
		}

		return [
			'moving_fn' => $array,
		];
	}

}

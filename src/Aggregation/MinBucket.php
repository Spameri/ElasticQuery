<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-min-bucket-aggregation.html
 */
class MinBucket implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $bucketsPath,
		private string|null $gapPolicy = null,
		private string|null $format = null,
		private string $key = 'min_bucket',
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
		];

		if ($this->gapPolicy !== null) {
			$array['gap_policy'] = $this->gapPolicy;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return [
			'min_bucket' => $array,
		];
	}

}

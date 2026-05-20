<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-serialdiff-aggregation.html
 */
class SerialDiff implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $bucketsPath,
		private int|null $lag = null,
		private string|null $gapPolicy = null,
		private string|null $format = null,
		private string $key = 'serial_diff',
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

		if ($this->lag !== null) {
			$array['lag'] = $this->lag;
		}

		if ($this->gapPolicy !== null) {
			$array['gap_policy'] = $this->gapPolicy;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return [
			'serial_diff' => $array,
		];
	}

}

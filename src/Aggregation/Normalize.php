<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-normalize-aggregation.html
 */
class Normalize implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $bucketsPath,
		private string $method,
		private string|null $format = null,
		private string $key = 'normalize',
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
			'method' => $this->method,
		];

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return [
			'normalize' => $array,
		];
	}

}

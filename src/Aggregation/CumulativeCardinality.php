<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-cumulative-cardinality-aggregation.html
 */
class CumulativeCardinality implements LeafAggregationInterface
{

	public function __construct(
		private string $bucketsPath,
		private string|null $format = null,
		private string $key = 'cumulative_cardinality',
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
		$array = ['buckets_path' => $this->bucketsPath];

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['cumulative_cardinality' => $array];
	}

}

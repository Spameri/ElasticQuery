<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-variablewidthhistogram-aggregation.html
 */
class VariableWidthHistogram implements LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int $buckets,
		private int|null $shardSize = null,
		private int|null $initialBuffer = null,
	)
	{
	}


	public function key(): string
	{
		return 'variable_width_histogram_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'buckets' => $this->buckets,
		];

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->initialBuffer !== null) {
			$array['initial_buffer'] = $this->initialBuffer;
		}

		return ['variable_width_histogram' => $array];
	}

}

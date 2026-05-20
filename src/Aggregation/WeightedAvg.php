<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-weight-avg-aggregation.html
 */
class WeightedAvg implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue $value,
		private \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue $weight,
		private string|null $format = null,
		private string $key = 'weighted_avg',
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
			'value' => $this->value->toArray(),
			'weight' => $this->weight->toArray(),
		];

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['weighted_avg' => $array];
	}

}

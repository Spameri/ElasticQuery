<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-weight-avg-aggregation.html
 */
class WeightedAvg implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $valueField,
		private string $weightField,
	)
	{
	}


	public function key(): string
	{
		return 'weighted_avg_' . $this->valueField;
	}


	/**
	 * @return array<string, array<string, array<string, string>>>
	 */
	public function toArray(): array
	{
		return [
			'weighted_avg' => [
				'value' => [
					'field' => $this->valueField,
				],
				'weight' => [
					'field' => $this->weightField,
				],
			],
		];
	}

}

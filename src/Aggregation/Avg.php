<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-avg-aggregation.html
 */
class Avg implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
	)
	{
	}


	public function key(): string
	{
		return 'avg_' . $this->field;
	}


	public function toArray(): array
	{
		return [
			'avg' => [
				'field' => $this->field,
			],
		];
	}

}

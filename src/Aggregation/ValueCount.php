<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-valuecount-aggregation.html
 */
class ValueCount implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
	)
	{
	}


	public function key(): string
	{
		return 'value_count_' . $this->field;
	}


	/**
	 * @return array<string, array<string, string>>
	 */
	public function toArray(): array
	{
		return [
			'value_count' => [
				'field' => $this->field,
			],
		];
	}

}

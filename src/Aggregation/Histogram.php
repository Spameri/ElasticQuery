<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html
 */
class Histogram implements LeafAggregationInterface
{

	private string $field;

	private int $interval;


	public function __construct(
		string $field,
		int $interval,
	)
	{
		$this->field = $field;
		$this->interval = $interval;
	}


	public function key(): string
	{
		return $this->field;
	}


	public function toArray(): array
	{
		return [
			'histogram' => [
				'field'    => $this->field,
				'interval' => $this->interval,
			],
		];
	}

}

<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
 */
class Range implements LeafAggregationInterface
{

	private \Spameri\ElasticQuery\Aggregation\RangeValueCollection $ranges;


	public function __construct(
		private string $field,
		private bool $keyed = FALSE,
		\Spameri\ElasticQuery\Aggregation\RangeValueCollection $rangeValueCollection = NULL,
	)
	{
		$this->ranges = $rangeValueCollection ?: new \Spameri\ElasticQuery\Aggregation\RangeValueCollection();
	}


	public function key(): string
	{
		return $this->field;
	}


	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->keyed === TRUE) {
			$array['keyed'] = TRUE;
		}

		foreach ($this->ranges as $range) {
			$array['ranges'][] = $range->toArray();
		}

		return [
			'range' => $array,
		];
	}


	public function ranges(): \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	{
		return $this->ranges;
	}

}

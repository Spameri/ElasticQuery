<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-median-absolute-deviation-aggregation.html
 */
class MedianAbsoluteDeviation implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $compression = null,
	)
	{
	}


	public function key(): string
	{
		return 'median_absolute_deviation_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->compression !== null) {
			$array['compression'] = $this->compression;
		}

		return [
			'median_absolute_deviation' => $array,
		];
	}

}

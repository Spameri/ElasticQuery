<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geobounds-aggregation.html
 */
class GeoBounds implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private bool $wrapLongitude = true,
	)
	{
	}


	public function key(): string
	{
		return 'geo_bounds_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->wrapLongitude === false) {
			$array['wrap_longitude'] = false;
		}

		return [
			'geo_bounds' => $array,
		];
	}

}

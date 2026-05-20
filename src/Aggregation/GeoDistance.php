<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geodistance-aggregation.html
 */
class GeoDistance implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private float $lat,
		private float $lon,
		private \Spameri\ElasticQuery\Aggregation\RangeValueCollection $ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(),
		private string|null $unit = null,
		private string|null $distanceType = null,
		private bool|null $keyed = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $missing = null,
	)
	{
	}


	public function key(): string
	{
		return 'geo_distance_' . $this->field;
	}


	public function ranges(): \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	{
		return $this->ranges;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'origin' => [
				'lat' => $this->lat,
				'lon' => $this->lon,
			],
		];

		if ($this->unit !== null) {
			$array['unit'] = $this->unit;
		}

		if ($this->distanceType !== null) {
			$array['distance_type'] = $this->distanceType;
		}

		if ($this->keyed !== null) {
			$array['keyed'] = $this->keyed;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		foreach ($this->ranges as $range) {
			$array['ranges'][] = $range->toArray();
		}

		return ['geo_distance' => $array];
	}

}

<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html
 */
class GeoDistance implements LeafQueryInterface
{

	public function __construct(
		private string $field,
		private float $lat,
		private float $lon,
		private string $distance,
		private string|null $distanceType = null,
		private string|null $validationMethod = null,
		private bool|null $ignoreUnmapped = null,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'geo_distance_' . $this->field . '_' . $this->lat . '.' . $this->lon . '_' . $this->distance;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			'distance' => $this->distance,
			$this->field => [
				'lat' => $this->lat,
				'lon' => $this->lon,
			],
			'boost' => $this->boost,
		];

		if ($this->distanceType !== null) {
			$body['distance_type'] = $this->distanceType;
		}

		if ($this->validationMethod !== null) {
			$body['validation_method'] = $this->validationMethod;
		}

		if ($this->ignoreUnmapped !== null) {
			$body['ignore_unmapped'] = $this->ignoreUnmapped;
		}

		return [
			'geo_distance' => $body,
		];
	}

}

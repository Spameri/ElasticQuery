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
	)
	{

	}


	public function key(): string
	{
		return 'geo_distance_' . $this->field . '_' . $this->lat . '.' . $this->lon;
	}


	public function toArray(): array
	{
		return [
			'pin' => [
				'location' => [
					'lat' => $this->lat,
					'lon' => $this->lon,
				],
			],
		];
	}

}

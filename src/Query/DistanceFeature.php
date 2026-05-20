<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-distance-feature-query.html
 */
class DistanceFeature implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	/**
	 * @param array<int, float>|string $origin Either [lat, lon] for geo_point or a date string for date.
	 */
	public function __construct(
		private string $field,
		private array|string $origin,
		private string $pivot,
		private float $boost = 1.0,
	)
	{
	}


	public function key(): string
	{
		return 'distance_feature_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		return [
			'distance_feature' => [
				'field' => $this->field,
				'origin' => $this->origin,
				'pivot' => $this->pivot,
				'boost' => $this->boost,
			],
		];
	}

}

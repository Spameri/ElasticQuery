<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geocentroid-aggregation.html
 */
class GeoCentroid implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
	)
	{
	}


	public function key(): string
	{
		return 'geo_centroid_' . $this->field;
	}


	/**
	 * @return array<string, array<string, string>>
	 */
	public function toArray(): array
	{
		return [
			'geo_centroid' => [
				'field' => $this->field,
			],
		];
	}

}

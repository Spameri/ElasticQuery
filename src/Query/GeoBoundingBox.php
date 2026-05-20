<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-bounding-box-query.html
 */
class GeoBoundingBox implements \Spameri\ElasticQuery\Query\LeafQueryInterface
{

	public function __construct(
		private string $field,
		private float $topLeftLat,
		private float $topLeftLon,
		private float $bottomRightLat,
		private float $bottomRightLon,
		private string|null $type = null,
	)
	{
	}


	public function key(): string
	{
		return 'geo_bounding_box_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = [
			$this->field => [
				'top_left' => [
					'lat' => $this->topLeftLat,
					'lon' => $this->topLeftLon,
				],
				'bottom_right' => [
					'lat' => $this->bottomRightLat,
					'lon' => $this->bottomRightLon,
				],
			],
		];

		if ($this->type !== null) {
			$body['type'] = $this->type;
		}

		return [
			'geo_bounding_box' => $body,
		];
	}

}

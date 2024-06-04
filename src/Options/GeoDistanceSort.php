<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Options;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/7.3/search-request-body.html#geo-sorting
 */
readonly class GeoDistanceSort implements \Spameri\ElasticQuery\Entity\EntityInterface
{

	public function __construct(
		public string $field,
		public float $lat,
		public float $lon,
		public string $type = Sort::ASC,
		public string $unit = 'km',
		public string $mode = 'min',
		public string $distanceType = 'arc',
	)
	{
		if ( ! \in_array($type, [Sort::ASC, Sort::DESC], true)) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'Sorting type ' . $type . ' is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
			);
		}
	}


	public function key(): string
	{
		return $this->field;
	}


	public function toArray(): array
	{
		return [
			'_geo_distance' => [
				$this->field => [
					$this->lat,
					$this->lon,
				],
				'order' => $this->type,
				"unit" => $this->unit,
				"mode" => $this->mode,
				"distance_type" => $this->distanceType,
				"ignore_unmapped" => true,
			],
		];
	}

}

<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Query;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html
 */
class GeoDistance implements LeafQueryInterface
{

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var float
	 */
	private $lat;

	/**
	 * @var float
	 */
	private $lon;


	public function __construct(
		string $field
		, float $lat
		, float $lon
	)
	{

		$this->field = $field;
		$this->lat = $lat;
		$this->lon = $lon;
	}


	public function key() : string
	{
		return 'geo_distance_' . $this->field . '_' . $this->lat . '.' . $this->lon;
	}


	public function toArray() : array
	{
		$array = [
			'pin' => [
				'location' => [
					'lat' => $this->lat,
					'lon' => $this->lon,
				],
			],
		];


		return $array;
	}

}

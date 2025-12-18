<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class GeoDistanceSort extends \Tester\TestCase
{

	public function testToArrayBasic(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 48.8566,
			lon: 2.3522,
		);

		$array = $geoSort->toArray();

		\Tester\Assert::true(isset($array['_geo_distance']));
		\Tester\Assert::same([48.8566, 2.3522], $array['_geo_distance']['location']);
		\Tester\Assert::same('ASC', $array['_geo_distance']['order']);
		\Tester\Assert::same('km', $array['_geo_distance']['unit']);
		\Tester\Assert::same('min', $array['_geo_distance']['mode']);
		\Tester\Assert::same('arc', $array['_geo_distance']['distance_type']);
		\Tester\Assert::same(true, $array['_geo_distance']['ignore_unmapped']);
	}


	public function testToArrayDescending(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 51.5074,
			lon: -0.1278,
			type: \Spameri\ElasticQuery\Options\Sort::DESC,
		);

		$array = $geoSort->toArray();

		\Tester\Assert::same('DESC', $array['_geo_distance']['order']);
	}


	public function testToArrayWithUnit(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 40.7128,
			lon: -74.0060,
			unit: 'mi',
		);

		$array = $geoSort->toArray();

		\Tester\Assert::same('mi', $array['_geo_distance']['unit']);
	}


	public function testToArrayWithModeAvg(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'locations',
			lat: 35.6762,
			lon: 139.6503,
			mode: 'avg',
		);

		$array = $geoSort->toArray();

		\Tester\Assert::same('avg', $array['_geo_distance']['mode']);
	}


	public function testToArrayWithDistanceTypePlane(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 52.5200,
			lon: 13.4050,
			distanceType: 'plane',
		);

		$array = $geoSort->toArray();

		\Tester\Assert::same('plane', $array['_geo_distance']['distance_type']);
	}


	public function testToArrayFullOptions(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'shop_location',
			lat: -33.8688,
			lon: 151.2093,
			type: \Spameri\ElasticQuery\Options\Sort::DESC,
			unit: 'm',
			mode: 'max',
			distanceType: 'plane',
		);

		$array = $geoSort->toArray();

		\Tester\Assert::same([-33.8688, 151.2093], $array['_geo_distance']['shop_location']);
		\Tester\Assert::same('DESC', $array['_geo_distance']['order']);
		\Tester\Assert::same('m', $array['_geo_distance']['unit']);
		\Tester\Assert::same('max', $array['_geo_distance']['mode']);
		\Tester\Assert::same('plane', $array['_geo_distance']['distance_type']);
	}


	public function testKey(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'office_location',
			lat: 50.0755,
			lon: 14.4378,
		);

		\Tester\Assert::same('office_location', $geoSort->key());
	}


	public function testReadonlyProperties(): void
	{
		$geoSort = new \Spameri\ElasticQuery\Options\GeoDistanceSort(
			field: 'location',
			lat: 48.2082,
			lon: 16.3738,
			type: \Spameri\ElasticQuery\Options\Sort::DESC,
			unit: 'mi',
			mode: 'avg',
			distanceType: 'plane',
		);

		\Tester\Assert::same('location', $geoSort->field);
		\Tester\Assert::same(48.2082, $geoSort->lat);
		\Tester\Assert::same(16.3738, $geoSort->lon);
		\Tester\Assert::same('DESC', $geoSort->type);
		\Tester\Assert::same('mi', $geoSort->unit);
		\Tester\Assert::same('avg', $geoSort->mode);
		\Tester\Assert::same('plane', $geoSort->distanceType);
	}


	public function testInvalidSortTypeThrowsException(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Options\GeoDistanceSort(
					field: 'location',
					lat: 0.0,
					lon: 0.0,
					type: 'INVALID',
				);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
			'Sorting type INVALID is out of allowed range. See \Spameri\ElasticQuery\Options\Sort for reference.',
		);
	}

}

(new GeoDistanceSort())->run();

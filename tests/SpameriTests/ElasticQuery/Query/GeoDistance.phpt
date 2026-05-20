<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoDistance extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_geo_distance';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'location' => ['type' => 'geo_point'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'location',
			40.73,
			-74.1,
			'200km',
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same('200km', $array['geo_distance']['distance']);
		\Tester\Assert::same(40.73, $array['geo_distance']['location']['lat']);
		\Tester\Assert::same(-74.1, $array['geo_distance']['location']['lon']);
		\Tester\Assert::same(1.0, $array['geo_distance']['boost']);
	}


	public function testWithAllOptions(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			field: 'location',
			lat: 48.8566,
			lon: 2.3522,
			distance: '10km',
			distanceType: 'plane',
			validationMethod: 'COERCE',
			ignoreUnmapped: true,
			boost: 2.0,
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same('plane', $array['geo_distance']['distance_type']);
		\Tester\Assert::same('COERCE', $array['geo_distance']['validation_method']);
		\Tester\Assert::true($array['geo_distance']['ignore_unmapped']);
		\Tester\Assert::same(2.0, $array['geo_distance']['boost']);
	}


	public function testKey(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'position',
			51.5074,
			-0.1278,
			'5km',
		);

		\Tester\Assert::same('geo_distance_position_51.5074.-0.1278_5km', $geoDistance->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['location' => ['lat' => 40.73, 'lon' => -74.1]]);
		$this->indexDocument(['location' => ['lat' => 0.0, 'lon' => 0.0]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\GeoDistance(
						field: 'location',
						lat: 40.73,
						lon: -74.1,
						distance: '50km',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new GeoDistance())->run();

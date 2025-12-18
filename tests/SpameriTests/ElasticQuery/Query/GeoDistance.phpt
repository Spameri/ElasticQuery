<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoDistance extends \Tester\TestCase
{

	public function testToArray(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'location',
			40.73,
			-74.1,
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::true(isset($array['pin']));
		\Tester\Assert::true(isset($array['pin']['location']));
		\Tester\Assert::same(40.73, $array['pin']['location']['lat']);
		\Tester\Assert::same(-74.1, $array['pin']['location']['lon']);
	}


	public function testKey(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'position',
			51.5074,
			-0.1278,
		);

		\Tester\Assert::same('geo_distance_position_51.5074.-0.1278', $geoDistance->key());
	}


	public function testWithDifferentCoordinates(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'coordinates',
			48.8566,
			2.3522,
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same(48.8566, $array['pin']['location']['lat']);
		\Tester\Assert::same(2.3522, $array['pin']['location']['lon']);
	}


	public function testNegativeCoordinates(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'location',
			-33.8688,
			-151.2093,
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same(-33.8688, $array['pin']['location']['lat']);
		\Tester\Assert::same(-151.2093, $array['pin']['location']['lon']);
	}


	public function testZeroCoordinates(): void
	{
		$geoDistance = new \Spameri\ElasticQuery\Query\GeoDistance(
			'location',
			0.0,
			0.0,
		);

		$array = $geoDistance->toArray();

		\Tester\Assert::same(0.0, $array['pin']['location']['lat']);
		\Tester\Assert::same(0.0, $array['pin']['location']['lon']);
	}

}

(new GeoDistance())->run();

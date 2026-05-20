<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class DistanceFeature extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_distance_feature';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayDate(): void
	{
		$df = new \Spameri\ElasticQuery\Query\DistanceFeature(
			field: 'production_date',
			origin: 'now',
			pivot: '7d',
		);

		$array = $df->toArray();

		\Tester\Assert::same('now', $array['distance_feature']['origin']);
		\Tester\Assert::same('7d', $array['distance_feature']['pivot']);
	}


	public function testToArrayGeo(): void
	{
		$df = new \Spameri\ElasticQuery\Query\DistanceFeature(
			field: 'location',
			origin: [50.0, 14.4],
			pivot: '1000m',
		);

		$array = $df->toArray();

		\Tester\Assert::same([50.0, 14.4], $array['distance_feature']['origin']);
	}


	public function testKey(): void
	{
		$df = new \Spameri\ElasticQuery\Query\DistanceFeature('location', [0, 0], '1km');

		\Tester\Assert::same('distance_feature_location', $df->key());
	}


	public function tearDown(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'DELETE');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}

}

(new DistanceFeature())->run();

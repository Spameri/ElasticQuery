<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoBoundingBox extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_geo_bounding_box';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArray(): void
	{
		$gbb = new \Spameri\ElasticQuery\Query\GeoBoundingBox(
			field: 'location',
			topLeftLat: 40.73,
			topLeftLon: -74.1,
			bottomRightLat: 40.01,
			bottomRightLon: -71.12,
		);

		$array = $gbb->toArray();

		\Tester\Assert::same(40.73, $array['geo_bounding_box']['location']['top_left']['lat']);
		\Tester\Assert::same(-71.12, $array['geo_bounding_box']['location']['bottom_right']['lon']);
	}


	public function testKey(): void
	{
		$gbb = new \Spameri\ElasticQuery\Query\GeoBoundingBox('location', 1, 1, 0, 0);

		\Tester\Assert::same('geo_bounding_box_location', $gbb->key());
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

(new GeoBoundingBox())->run();

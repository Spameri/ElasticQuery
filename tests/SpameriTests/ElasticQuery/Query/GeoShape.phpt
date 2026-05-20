<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class GeoShape extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_geo_shape';


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
		$geoShape = new \Spameri\ElasticQuery\Query\GeoShape(
			field: 'location',
			shape: [
				'type' => 'envelope',
				'coordinates' => [[13.0, 53.0], [14.0, 52.0]],
			],
			relation: 'within',
		);

		$array = $geoShape->toArray();

		\Tester\Assert::same('envelope', $array['geo_shape']['location']['shape']['type']);
		\Tester\Assert::same('within', $array['geo_shape']['location']['relation']);
	}


	public function testRelationValidated(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\GeoShape('loc', ['type' => 'point', 'coordinates' => [0, 0]], 'nonsense');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$geoShape = new \Spameri\ElasticQuery\Query\GeoShape('location', ['type' => 'point', 'coordinates' => [0, 0]]);

		\Tester\Assert::same('geo_shape_location', $geoShape->key());
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

(new GeoShape())->run();

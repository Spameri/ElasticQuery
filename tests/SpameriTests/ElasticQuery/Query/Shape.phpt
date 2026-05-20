<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Shape extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_shape';


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
		$shape = new \Spameri\ElasticQuery\Query\Shape(
			field: 'geometry',
			shape: ['type' => 'envelope', 'coordinates' => [[0, 100], [100, 0]]],
			relation: 'intersects',
		);

		$array = $shape->toArray();

		\Tester\Assert::same('envelope', $array['shape']['geometry']['shape']['type']);
	}


	public function testKey(): void
	{
		$shape = new \Spameri\ElasticQuery\Query\Shape('geometry', ['type' => 'point', 'coordinates' => [0, 0]]);

		\Tester\Assert::same('shape_geometry', $shape->key());
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

(new Shape())->run();

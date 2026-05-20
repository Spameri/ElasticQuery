<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Derivative extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_derivative';


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
		$derivative = new \Spameri\ElasticQuery\Aggregation\Derivative(
			bucketsPath: 'sales',
			unit: 'day',
		);

		$array = $derivative->toArray();

		\Tester\Assert::same('sales', $array['derivative']['buckets_path']);
		\Tester\Assert::same('day', $array['derivative']['unit']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'derivative',
			(new \Spameri\ElasticQuery\Aggregation\Derivative('p'))->key(),
		);
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

(new Derivative())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class MaxBucket extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_max_bucket';


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
		$maxBucket = new \Spameri\ElasticQuery\Aggregation\MaxBucket(
			bucketsPath: 'sales_per_month>sales',
		);

		$array = $maxBucket->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['max_bucket']['buckets_path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'max_bucket',
			(new \Spameri\ElasticQuery\Aggregation\MaxBucket('p'))->key(),
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

(new MaxBucket())->run();

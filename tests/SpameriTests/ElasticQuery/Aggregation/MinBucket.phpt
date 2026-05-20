<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class MinBucket extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_min_bucket';


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
		$minBucket = new \Spameri\ElasticQuery\Aggregation\MinBucket(
			bucketsPath: 'sales_per_month>sales',
		);

		$array = $minBucket->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['min_bucket']['buckets_path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'min_bucket',
			(new \Spameri\ElasticQuery\Aggregation\MinBucket('p'))->key(),
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

(new MinBucket())->run();

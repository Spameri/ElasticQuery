<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class AvgBucket extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_avg_bucket';


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
		$avgBucket = new \Spameri\ElasticQuery\Aggregation\AvgBucket(
			bucketsPath: 'sales_per_month>sales',
		);

		$array = $avgBucket->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['avg_bucket']['buckets_path']);
	}


	public function testToArrayWithGapPolicy(): void
	{
		$avgBucket = new \Spameri\ElasticQuery\Aggregation\AvgBucket(
			bucketsPath: 'sales_per_month>sales',
			gapPolicy: 'skip',
			format: '0.00',
		);

		$array = $avgBucket->toArray();

		\Tester\Assert::same('skip', $array['avg_bucket']['gap_policy']);
		\Tester\Assert::same('0.00', $array['avg_bucket']['format']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'avg_bucket',
			(new \Spameri\ElasticQuery\Aggregation\AvgBucket('p'))->key(),
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

(new AvgBucket())->run();

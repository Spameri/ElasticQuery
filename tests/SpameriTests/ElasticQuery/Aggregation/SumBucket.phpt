<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class SumBucket extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_sum_bucket';


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
		$sumBucket = new \Spameri\ElasticQuery\Aggregation\SumBucket(
			bucketsPath: 'sales_per_month>sales',
		);

		$array = $sumBucket->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['sum_bucket']['buckets_path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'sum_bucket',
			(new \Spameri\ElasticQuery\Aggregation\SumBucket('p'))->key(),
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

(new SumBucket())->run();

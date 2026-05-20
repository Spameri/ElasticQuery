<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class BucketSelector extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_bucket_selector';


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
		$selector = new \Spameri\ElasticQuery\Aggregation\BucketSelector(
			bucketsPath: ['totalSales' => 'total_sales'],
			script: 'params.totalSales > 100',
		);

		$array = $selector->toArray();

		\Tester\Assert::same('total_sales', $array['bucket_selector']['buckets_path']['totalSales']);
		\Tester\Assert::same('params.totalSales > 100', $array['bucket_selector']['script']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'bucket_selector',
			(new \Spameri\ElasticQuery\Aggregation\BucketSelector(['a' => 'b'], 'params.a'))->key(),
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

(new BucketSelector())->run();

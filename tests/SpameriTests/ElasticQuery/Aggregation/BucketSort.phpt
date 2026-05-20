<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class BucketSort extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_bucket_sort';


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
		$bucketSort = new \Spameri\ElasticQuery\Aggregation\BucketSort(
			sort: [['total_sales' => ['order' => 'desc']]],
			size: 5,
			from: 0,
		);

		$array = $bucketSort->toArray();

		\Tester\Assert::same('desc', $array['bucket_sort']['sort'][0]['total_sales']['order']);
		\Tester\Assert::same(5, $array['bucket_sort']['size']);
		\Tester\Assert::same(0, $array['bucket_sort']['from']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'bucket_sort',
			(new \Spameri\ElasticQuery\Aggregation\BucketSort())->key(),
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

(new BucketSort())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class BucketScript extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_bucket_script';


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
		$bs = new \Spameri\ElasticQuery\Aggregation\BucketScript(
			bucketsPath: ['tShirts' => 't-shirts', 'total' => 'total_sales'],
			script: 'params.tShirts / params.total * 100',
		);

		$array = $bs->toArray();

		\Tester\Assert::same('t-shirts', $array['bucket_script']['buckets_path']['tShirts']);
		\Tester\Assert::same('params.tShirts / params.total * 100', $array['bucket_script']['script']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'bucket_script',
			(new \Spameri\ElasticQuery\Aggregation\BucketScript(['a' => 'b'], 'params.a'))->key(),
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

(new BucketScript())->run();

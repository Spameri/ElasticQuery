<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class CumulativeSum extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_cumulative_sum';


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
		$cumSum = new \Spameri\ElasticQuery\Aggregation\CumulativeSum(
			bucketsPath: 'sales',
		);

		$array = $cumSum->toArray();

		\Tester\Assert::same('sales', $array['cumulative_sum']['buckets_path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'cumulative_sum',
			(new \Spameri\ElasticQuery\Aggregation\CumulativeSum('p'))->key(),
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

(new CumulativeSum())->run();

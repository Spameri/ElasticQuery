<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Normalize extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_normalize';


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
		$normalize = new \Spameri\ElasticQuery\Aggregation\Normalize(
			bucketsPath: 'sales',
			method: 'percent_of_sum',
		);

		$array = $normalize->toArray();

		\Tester\Assert::same('sales', $array['normalize']['buckets_path']);
		\Tester\Assert::same('percent_of_sum', $array['normalize']['method']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'normalize',
			(new \Spameri\ElasticQuery\Aggregation\Normalize('p', 'rescale_0_1'))->key(),
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

(new Normalize())->run();

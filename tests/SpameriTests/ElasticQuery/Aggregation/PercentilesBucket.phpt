<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class PercentilesBucket extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_percentiles_bucket';


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
		$pb = new \Spameri\ElasticQuery\Aggregation\PercentilesBucket(
			bucketsPath: 'sales_per_month>sales',
			percents: [50, 95, 99],
		);

		$array = $pb->toArray();

		\Tester\Assert::same('sales_per_month>sales', $array['percentiles_bucket']['buckets_path']);
		\Tester\Assert::same([50, 95, 99], $array['percentiles_bucket']['percents']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'percentiles_bucket',
			(new \Spameri\ElasticQuery\Aggregation\PercentilesBucket('p'))->key(),
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

(new PercentilesBucket())->run();

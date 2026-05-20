<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class SerialDiff extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_serial_diff';


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
		$serialDiff = new \Spameri\ElasticQuery\Aggregation\SerialDiff(
			bucketsPath: 'sales',
			lag: 7,
		);

		$array = $serialDiff->toArray();

		\Tester\Assert::same('sales', $array['serial_diff']['buckets_path']);
		\Tester\Assert::same(7, $array['serial_diff']['lag']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'serial_diff',
			(new \Spameri\ElasticQuery\Aggregation\SerialDiff('p'))->key(),
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

(new SerialDiff())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class MovingFunction extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_aggregation_moving_function';


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
		$movFn = new \Spameri\ElasticQuery\Aggregation\MovingFunction(
			bucketsPath: 'sales',
			window: 5,
			script: 'MovingFunctions.unweightedAvg(values)',
		);

		$array = $movFn->toArray();

		\Tester\Assert::same('sales', $array['moving_fn']['buckets_path']);
		\Tester\Assert::same(5, $array['moving_fn']['window']);
		\Tester\Assert::same('MovingFunctions.unweightedAvg(values)', $array['moving_fn']['script']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'moving_fn',
			(new \Spameri\ElasticQuery\Aggregation\MovingFunction('p', 5, 'script'))->key(),
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

(new MovingFunction())->run();

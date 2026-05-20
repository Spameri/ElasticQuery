<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Intervals extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_intervals';


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
		$intervals = new \Spameri\ElasticQuery\Query\Intervals(
			field: 'my_text',
			rule: [
				'match' => [
					'query' => 'my favorite food',
					'max_gaps' => 0,
					'ordered' => true,
				],
			],
		);

		$array = $intervals->toArray();

		\Tester\Assert::same('my favorite food', $array['intervals']['my_text']['match']['query']);
	}


	public function testRequiresRule(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Intervals('f', []);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$intervals = new \Spameri\ElasticQuery\Query\Intervals('f', ['match' => ['query' => 'x']]);

		\Tester\Assert::same('intervals_f', $intervals->key());
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

(new Intervals())->run();

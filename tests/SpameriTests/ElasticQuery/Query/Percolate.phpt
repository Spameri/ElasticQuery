<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Percolate extends \Tester\TestCase
{

	private const INDEX = 'spameri_test_query_percolate';


	public function setUp(): void
	{
		$ch = \curl_init();
		\curl_setopt($ch, \CURLOPT_URL, \ELASTICSEARCH_HOST . '/' . self::INDEX);
		\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		\curl_exec($ch);
	}


	public function testToArrayInline(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			document: ['message' => 'A new bonsai tree'],
		);

		$array = $percolate->toArray();

		\Tester\Assert::same(['message' => 'A new bonsai tree'], $array['percolate']['document']);
	}


	public function testToArrayById(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			index: 'my-index',
			id: '1',
		);

		$array = $percolate->toArray();

		\Tester\Assert::same('my-index', $array['percolate']['index']);
		\Tester\Assert::same('1', $array['percolate']['id']);
	}


	public function testRequiresDocOrId(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Percolate(field: 'query');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			document: ['m' => 'hello'],
		);

		\Tester\Assert::same('percolate_query', $percolate->key());
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

(new Percolate())->run();

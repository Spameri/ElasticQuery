<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Percolate extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_percolate';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'query' => ['type' => 'percolator'],
					'message' => ['type' => 'text'],
				],
			],
		];
	}


	public function testToArrayInline(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			document: ['message' => 'A new bonsai tree'],
		);

		\Tester\Assert::same(['message' => 'A new bonsai tree'], $percolate->toArray()['percolate']['document']);
	}


	public function testToArrayDocuments(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			documents: [
				['message' => 'hello'],
				['message' => 'world'],
			],
			name: 'docs',
		);

		$array = $percolate->toArray();

		\Tester\Assert::count(2, $array['percolate']['documents']);
		\Tester\Assert::same('docs', $array['percolate']['name']);
	}


	public function testToArrayById(): void
	{
		$percolate = new \Spameri\ElasticQuery\Query\Percolate(
			field: 'query',
			index: 'my-index',
			id: '1',
			routing: 'r',
			preference: 'p',
			version: 7,
		);

		$array = $percolate->toArray();

		\Tester\Assert::same('my-index', $array['percolate']['index']);
		\Tester\Assert::same('1', $array['percolate']['id']);
		\Tester\Assert::same('r', $array['percolate']['routing']);
		\Tester\Assert::same('p', $array['percolate']['preference']);
		\Tester\Assert::same(7, $array['percolate']['version']);
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


	public function testCreate(): void
	{
		$this->request(
			'PUT',
			self::INDEX . '/_doc/1?refresh=true',
			['query' => ['match' => ['message' => 'bonsai']]],
		);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Percolate(
						field: 'query',
						document: ['message' => 'A new bonsai tree in the garden'],
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Percolate())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Knn extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_knn';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'vector' => [
						'type' => 'dense_vector',
						'dims' => 3,
						'index' => true,
						'similarity' => 'l2_norm',
					],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$knn = new \Spameri\ElasticQuery\Query\Knn(
			field: 'vector',
			queryVector: [1.0, 2.0, 3.0],
			k: 5,
			numCandidates: 50,
			similarity: 0.7,
			boost: 1.5,
		);

		$array = $knn->toArray();

		\Tester\Assert::same('vector', $array['knn']['field']);
		\Tester\Assert::same([1.0, 2.0, 3.0], $array['knn']['query_vector']);
		\Tester\Assert::same(5, $array['knn']['k']);
		\Tester\Assert::same(50, $array['knn']['num_candidates']);
		\Tester\Assert::same(0.7, $array['knn']['similarity']);
		\Tester\Assert::same(1.5, $array['knn']['boost']);
	}


	public function testWithFilter(): void
	{
		$knn = new \Spameri\ElasticQuery\Query\Knn(
			field: 'vector',
			queryVector: [1.0, 2.0, 3.0],
			k: 5,
			numCandidates: 50,
			filter: new \Spameri\ElasticQuery\Query\Term('status', 'published'),
		);

		\Tester\Assert::same('published', $knn->toArray()['knn']['filter']['term']['status']['value']);
	}


	public function testRequiresQueryVector(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Knn('v', [], 1, 10);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['vector' => [1.0, 2.0, 3.0]]);
		$this->indexDocument(['vector' => [10.0, 10.0, 10.0]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Knn(
						field: 'vector',
						queryVector: [1.1, 2.1, 3.1],
						k: 1,
						numCandidates: 10,
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Knn())->run();

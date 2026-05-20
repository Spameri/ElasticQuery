<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class SparseVector extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_sparse_vector';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'tokens' => ['type' => 'sparse_vector'],
				],
			],
		];
	}


	public function testToArrayWithVector(): void
	{
		$sv = new \Spameri\ElasticQuery\Query\SparseVector(
			field: 'tokens',
			queryVector: ['lion' => 0.5, 'tiger' => 0.7],
		);

		$array = $sv->toArray();

		\Tester\Assert::same('tokens', $array['sparse_vector']['field']);
		\Tester\Assert::same(0.5, $array['sparse_vector']['query_vector']['lion']);
	}


	public function testToArrayWithInference(): void
	{
		$sv = new \Spameri\ElasticQuery\Query\SparseVector(
			field: 'tokens',
			inferenceId: '.elser_model_2',
			query: 'big cat',
			prune: true,
			pruningConfig: ['tokens_freq_ratio_threshold' => 5],
		);

		$array = $sv->toArray();

		\Tester\Assert::same('.elser_model_2', $array['sparse_vector']['inference_id']);
		\Tester\Assert::same('big cat', $array['sparse_vector']['query']);
		\Tester\Assert::true($array['sparse_vector']['prune']);
	}


	public function testRequiresVectorOrInference(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\SparseVector('f');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['tokens' => ['lion' => 0.5, 'cat' => 0.3]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\SparseVector(
						field: 'tokens',
						queryVector: ['lion' => 0.5, 'tiger' => 0.7],
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new SparseVector())->run();

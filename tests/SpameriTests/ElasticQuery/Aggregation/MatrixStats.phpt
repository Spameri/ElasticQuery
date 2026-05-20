<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class MatrixStats extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_matrix_stats';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'income' => ['type' => 'long'],
					'expense' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\MatrixStats(
			fields: ['income', 'expense'],
			missing: ['income' => 0],
			mode: 'avg',
		);

		$array = $agg->toArray();

		\Tester\Assert::same(['income', 'expense'], $array['matrix_stats']['fields']);
		\Tester\Assert::same('avg', $array['matrix_stats']['mode']);
	}


	public function testRequiresFields(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\MatrixStats([]);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['income' => 100, 'expense' => 50]);
		$this->indexDocument(['income' => 200, 'expense' => 80]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'corr', null, new \Spameri\ElasticQuery\Aggregation\MatrixStats(['income', 'expense']),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new MatrixStats())->run();

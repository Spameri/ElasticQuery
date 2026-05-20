<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Cardinality extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_cardinality';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['user_id' => ['type' => 'keyword']]]];
	}


	public function testToArray(): void
	{
		$cardinality = new \Spameri\ElasticQuery\Aggregation\Cardinality('user_id');
		\Tester\Assert::same('user_id', $cardinality->toArray()['cardinality']['field']);
	}


	public function testToArrayWithOptions(): void
	{
		$cardinality = new \Spameri\ElasticQuery\Aggregation\Cardinality(
			field: 'user_id',
			precisionThreshold: 3000,
			missing: 'none',
		);
		$array = $cardinality->toArray();
		\Tester\Assert::same(3000, $array['cardinality']['precision_threshold']);
		\Tester\Assert::same('none', $array['cardinality']['missing']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['user_id' => 'a']);
		$this->indexDocument(['user_id' => 'b']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'distinct_users', null, new \Spameri\ElasticQuery\Aggregation\Cardinality('user_id'),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Cardinality())->run();

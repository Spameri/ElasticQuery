<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class VariableWidthHistogram extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_variable_width_histogram';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\VariableWidthHistogram(
			field: 'price',
			buckets: 3,
			shardSize: 10,
			initialBuffer: 100,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('price', $array['variable_width_histogram']['field']);
		\Tester\Assert::same(3, $array['variable_width_histogram']['buckets']);
		\Tester\Assert::same(10, $array['variable_width_histogram']['shard_size']);
		\Tester\Assert::same(100, $array['variable_width_histogram']['initial_buffer']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 10]);
		$this->indexDocument(['price' => 50]);
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'price_buckets', null, new \Spameri\ElasticQuery\Aggregation\VariableWidthHistogram('price', 2),
		));

		\Tester\Assert::same(3, $this->search($elasticQuery)->stats()->total());
	}

}

(new VariableWidthHistogram())->run();

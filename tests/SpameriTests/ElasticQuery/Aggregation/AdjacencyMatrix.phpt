<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class AdjacencyMatrix extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_adjacency_matrix';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['status' => ['type' => 'keyword']]]];
	}


	public function testToArray(): void
	{
		$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix();
		$matrix->addFilter('active', new \Spameri\ElasticQuery\Query\Term('status', 'active'));

		$array = $matrix->toArray();

		\Tester\Assert::same(
			'active',
			$array['adjacency_matrix']['filters']['active']['term']['status']['value'],
		);
	}


	public function testToArrayWithSeparator(): void
	{
		$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix(separator: '|');

		\Tester\Assert::same('|', $matrix->toArray()['adjacency_matrix']['separator']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same(
			'adjacency_matrix',
			(new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix())->key(),
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['status' => 'active']);
		$this->indexDocument(['status' => 'inactive']);

		$matrix = new \Spameri\ElasticQuery\Aggregation\AdjacencyMatrix();
		$matrix->addFilter('group_active', new \Spameri\ElasticQuery\Query\Term('status', 'active'));
		$matrix->addFilter('group_inactive', new \Spameri\ElasticQuery\Query\Term('status', 'inactive'));

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'matrix', null, $matrix,
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new AdjacencyMatrix())->run();

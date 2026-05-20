<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class ReverseNested extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_reverse_nested';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'name' => ['type' => 'keyword'],
					'comments' => [
						'type' => 'nested',
						'properties' => [
							'author' => ['type' => 'keyword'],
						],
					],
				],
			],
		];
	}


	public function testToArrayWithoutPath(): void
	{
		$reverseNested = new \Spameri\ElasticQuery\Aggregation\ReverseNested();

		$array = $reverseNested->toArray();

		\Tester\Assert::type(\stdClass::class, $array['reverse_nested']);
	}


	public function testToArrayWithPath(): void
	{
		$reverseNested = new \Spameri\ElasticQuery\Aggregation\ReverseNested('parent');

		\Tester\Assert::same('parent', $reverseNested->toArray()['reverse_nested']['path']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same('reverse_nested_root', (new \Spameri\ElasticQuery\Aggregation\ReverseNested())->key());
		\Tester\Assert::same('reverse_nested_parent', (new \Spameri\ElasticQuery\Aggregation\ReverseNested('parent'))->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument([
			'name' => 'post',
			'comments' => [['author' => 'john']],
		]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();

		// reverse_nested must live inside a nested agg
		$reverseNestedAgg = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'back_to_root',
			null,
			new \Spameri\ElasticQuery\Aggregation\ReverseNested(),
		);

		$nestedAgg = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'comments_nested',
			null,
			new \Spameri\ElasticQuery\Aggregation\Nested('comments'),
			$reverseNestedAgg,
		);

		$elasticQuery->aggregation()->add($nestedAgg);

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new ReverseNested())->run();

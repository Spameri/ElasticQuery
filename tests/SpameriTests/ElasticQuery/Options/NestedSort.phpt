<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Options;

require_once __DIR__ . '/../../bootstrap.php';


class NestedSort extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_options_nested_sort';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'comments' => [
						'type' => 'nested',
						'properties' => [
							'rating' => ['type' => 'long'],
							'author' => ['type' => 'keyword'],
						],
					],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$nestedSort = new \Spameri\ElasticQuery\Options\NestedSort(
			path: 'comments',
			filter: new \Spameri\ElasticQuery\Query\Term('comments.author', 'john'),
			maxChildren: 5,
		);

		$array = $nestedSort->toArray();

		\Tester\Assert::same('comments', $array['path']);
		\Tester\Assert::same(5, $array['max_children']);
		\Tester\Assert::same('john', $array['filter']['term']['comments.author']['value']);
	}


	public function testSortWithNested(): void
	{
		$nestedSort = new \Spameri\ElasticQuery\Options\NestedSort(
			path: 'comments',
		);
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			field: 'comments.rating',
			type: \Spameri\ElasticQuery\Options\Sort::DESC,
			mode: 'avg',
			nested: $nestedSort,
		);

		$array = $sort->toArray();

		\Tester\Assert::same('avg', $array['comments.rating']['mode']);
		\Tester\Assert::same('comments', $array['comments.rating']['nested']['path']);
	}


	public function testCreate(): void
	{
		$this->indexDocument([
			'comments' => [
				['author' => 'john', 'rating' => 5],
				['author' => 'jane', 'rating' => 3],
			],
		]);

		$nestedSort = new \Spameri\ElasticQuery\Options\NestedSort(path: 'comments');
		$sort = new \Spameri\ElasticQuery\Options\Sort(
			field: 'comments.rating',
			type: \Spameri\ElasticQuery\Options\Sort::DESC,
			mode: 'avg',
			nested: $nestedSort,
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->options()->sort()->add($sort);
		$elasticQuery->addMustQuery(new \Spameri\ElasticQuery\Query\MatchAll());

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new NestedSort())->run();

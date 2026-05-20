<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Nested extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_nested';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'comments' => [
						'type' => 'nested',
						'properties' => [
							'author' => ['type' => 'keyword'],
							'text' => ['type' => 'text'],
						],
					],
				],
			],
		];
	}


	public function testToArrayBasic(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('comments');

		$array = $nested->toArray();

		\Tester\Assert::same('comments', $array['nested']['path']);
		\Tester\Assert::true(isset($array['nested']['query']['bool']));
	}


	public function testToArrayWithQuery(): void
	{
		$queryCollection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$queryCollection->addMustQuery(
			new \Spameri\ElasticQuery\Query\Term('comments.author', 'John'),
		);

		$nested = new \Spameri\ElasticQuery\Query\Nested('comments', $queryCollection);

		$array = $nested->toArray();

		\Tester\Assert::same('comments', $array['nested']['path']);
		\Tester\Assert::count(1, $array['nested']['query']['bool']['must']);
	}


	public function testToArrayWithScoreModeAndIgnoreUnmapped(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested(
			path: 'comments',
			scoreMode: \Spameri\ElasticQuery\Query\Nested::SCORE_MODE_AVG,
			ignoreUnmapped: true,
		);

		$array = $nested->toArray();

		\Tester\Assert::same('avg', $array['nested']['score_mode']);
		\Tester\Assert::true($array['nested']['ignore_unmapped']);
	}


	public function testToArrayWithInnerHits(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested(
			path: 'comments',
			innerHits: new \Spameri\ElasticQuery\Query\InnerHits(name: 'matched_comments', size: 5),
		);

		$array = $nested->toArray();

		\Tester\Assert::same('matched_comments', $array['nested']['inner_hits']['name']);
		\Tester\Assert::same(5, $array['nested']['inner_hits']['size']);
	}


	public function testGetQuery(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('products');

		\Tester\Assert::type(\Spameri\ElasticQuery\Query\QueryCollection::class, $nested->getQuery());
	}


	public function testKey(): void
	{
		$nested = new \Spameri\ElasticQuery\Query\Nested('reviews');

		\Tester\Assert::same('nested_reviews', $nested->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument([
			'comments' => [
				['author' => 'John', 'text' => 'great'],
				['author' => 'Jane', 'text' => 'bad'],
			],
		]);

		$queryCollection = new \Spameri\ElasticQuery\Query\QueryCollection();
		$queryCollection->addMustQuery(
			new \Spameri\ElasticQuery\Query\Term('comments.author', 'John'),
		);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Nested('comments', $queryCollection),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Nested())->run();

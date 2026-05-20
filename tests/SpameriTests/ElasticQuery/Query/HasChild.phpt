<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class HasChild extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_has_child';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'my_join_field' => [
						'type' => 'join',
						'relations' => ['blog' => 'comment'],
					],
					'author' => ['type' => 'keyword'],
					'tag' => ['type' => 'keyword'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$hasChild = new \Spameri\ElasticQuery\Query\HasChild(
			type: 'comment',
			query: new \Spameri\ElasticQuery\Query\Term('author', 'john'),
			scoreMode: 'max',
			minChildren: 1,
		);

		$array = $hasChild->toArray();

		\Tester\Assert::same('comment', $array['has_child']['type']);
		\Tester\Assert::same('max', $array['has_child']['score_mode']);
		\Tester\Assert::same(1, $array['has_child']['min_children']);
	}


	public function testToArrayWithInnerHits(): void
	{
		$hasChild = new \Spameri\ElasticQuery\Query\HasChild(
			type: 'comment',
			query: new \Spameri\ElasticQuery\Query\Term('author', 'john'),
			innerHits: new \Spameri\ElasticQuery\Query\InnerHits(name: 'matched', size: 3),
		);

		$array = $hasChild->toArray();

		\Tester\Assert::same('matched', $array['has_child']['inner_hits']['name']);
		\Tester\Assert::same(3, $array['has_child']['inner_hits']['size']);
	}


	public function testKey(): void
	{
		$hasChild = new \Spameri\ElasticQuery\Query\HasChild(
			'comment',
			new \Spameri\ElasticQuery\Query\Term('author', 'john'),
		);

		\Tester\Assert::same('has_child_comment', $hasChild->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['tag' => 'tech', 'my_join_field' => 'blog'], id: '1');
		$this->request(
			'PUT',
			self::INDEX . '/_doc/2?refresh=true&routing=1',
			['author' => 'john', 'my_join_field' => ['name' => 'comment', 'parent' => '1']],
		);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\HasChild(
						type: 'comment',
						query: new \Spameri\ElasticQuery\Query\Term('author', 'john'),
						innerHits: new \Spameri\ElasticQuery\Query\InnerHits(),
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new HasChild())->run();

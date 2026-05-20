<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class HasParent extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_has_parent';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'my_join_field' => [
						'type' => 'join',
						'relations' => ['blog' => 'comment'],
					],
					'tag' => ['type' => 'keyword'],
					'author' => ['type' => 'keyword'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$hasParent = new \Spameri\ElasticQuery\Query\HasParent(
			parentType: 'blog',
			query: new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
			score: true,
		);

		$array = $hasParent->toArray();

		\Tester\Assert::same('blog', $array['has_parent']['parent_type']);
		\Tester\Assert::true($array['has_parent']['score']);
	}


	public function testToArrayWithInnerHits(): void
	{
		$hasParent = new \Spameri\ElasticQuery\Query\HasParent(
			parentType: 'blog',
			query: new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
			innerHits: new \Spameri\ElasticQuery\Query\InnerHits(name: 'parent'),
		);

		\Tester\Assert::same('parent', $hasParent->toArray()['has_parent']['inner_hits']['name']);
	}


	public function testKey(): void
	{
		$hasParent = new \Spameri\ElasticQuery\Query\HasParent(
			'blog',
			new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
		);

		\Tester\Assert::same('has_parent_blog', $hasParent->key());
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
					new \Spameri\ElasticQuery\Query\HasParent(
						parentType: 'blog',
						query: new \Spameri\ElasticQuery\Query\Term('tag', 'tech'),
						score: true,
						innerHits: new \Spameri\ElasticQuery\Query\InnerHits(),
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new HasParent())->run();

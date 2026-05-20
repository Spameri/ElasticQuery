<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class ParentId extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_parent_id';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'my_join_field' => [
						'type' => 'join',
						'relations' => ['blog' => 'comment'],
					],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$parentId = new \Spameri\ElasticQuery\Query\ParentId(type: 'comment', id: '1', boost: 2.0);

		$array = $parentId->toArray();

		\Tester\Assert::same('comment', $array['parent_id']['type']);
		\Tester\Assert::same('1', $array['parent_id']['id']);
		\Tester\Assert::same(2.0, $array['parent_id']['boost']);
	}


	public function testKey(): void
	{
		$parentId = new \Spameri\ElasticQuery\Query\ParentId('comment', '1');
		\Tester\Assert::same('parent_id_comment_1', $parentId->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['my_join_field' => 'blog'], id: '1');
		$this->request(
			'PUT',
			self::INDEX . '/_doc/2?refresh=true&routing=1',
			['my_join_field' => ['name' => 'comment', 'parent' => '1']],
		);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\ParentId(type: 'comment', id: '1'),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new ParentId())->run();

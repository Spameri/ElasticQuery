<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Shape extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_shape';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['geometry' => ['type' => 'shape']]]];
	}


	public function testToArray(): void
	{
		$shape = new \Spameri\ElasticQuery\Query\Shape(
			field: 'geometry',
			shape: ['type' => 'envelope', 'coordinates' => [[0, 100], [100, 0]]],
			relation: 'intersects',
		);

		$array = $shape->toArray();

		\Tester\Assert::same('envelope', $array['shape']['geometry']['shape']['type']);
		\Tester\Assert::same('intersects', $array['shape']['geometry']['relation']);
	}


	public function testToArrayWithIndexedShape(): void
	{
		$shape = new \Spameri\ElasticQuery\Query\Shape(
			field: 'geometry',
			indexedShape: new \Spameri\ElasticQuery\Query\IndexedShape(
				id: 'box',
				index: 'shapes',
				path: 'geometry',
			),
			boost: 1.5,
		);

		\Tester\Assert::same('box', $shape->toArray()['shape']['geometry']['indexed_shape']['id']);
		\Tester\Assert::same(1.5, $shape->toArray()['shape']['boost']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['geometry' => ['type' => 'point', 'coordinates' => [10, 10]]]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Shape(
						field: 'geometry',
						shape: ['type' => 'envelope', 'coordinates' => [[0, 100], [100, 0]]],
						relation: 'intersects',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Shape())->run();

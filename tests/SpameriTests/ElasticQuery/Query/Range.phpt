<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Query;

require_once __DIR__ . '/../../bootstrap.php';


class Range extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_query_range';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'id' => ['type' => 'long'],
					'created' => ['type' => 'date'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$range = new \Spameri\ElasticQuery\Query\Range('id', 1, 1000000, 1.0);

		$array = $range->toArray();

		\Tester\Assert::same(1, $array['range']['id']['gte']);
		\Tester\Assert::same(1000000, $array['range']['id']['lte']);
		\Tester\Assert::same(1.0, $array['range']['id']['boost']);
	}


	public function testToArrayWithGtLtRelationFormat(): void
	{
		$range = new \Spameri\ElasticQuery\Query\Range(
			field: 'id',
			boost: 1.0,
			gt: 1,
			lt: 10,
			relation: \Spameri\ElasticQuery\Query\Range\Relation::WITHIN,
			format: 'epoch_second',
			timeZone: 'UTC',
		);

		$array = $range->toArray();

		\Tester\Assert::same(1, $array['range']['id']['gt']);
		\Tester\Assert::same(10, $array['range']['id']['lt']);
		\Tester\Assert::same('WITHIN', $array['range']['id']['relation']);
		\Tester\Assert::same('epoch_second', $array['range']['id']['format']);
		\Tester\Assert::same('UTC', $array['range']['id']['time_zone']);
	}


	public function testRejectsEmptyRange(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Range('id');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testRejectsInvalidRelation(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Query\Range('id', 1, 10, relation: 'OVERLAPS');
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['id' => 5]);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Range('id', 1, 10),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}


	public function testCreateWithAllOptions(): void
	{
		$this->indexDocument(['id' => 5, 'created' => '2024-01-01']);

		$query = new \Spameri\ElasticQuery\ElasticQuery(
			new \Spameri\ElasticQuery\Query\QueryCollection(
				null,
				new \Spameri\ElasticQuery\Query\MustCollection(
					new \Spameri\ElasticQuery\Query\Range(
						field: 'created',
						gte: '2023-01-01',
						lte: '2025-01-01',
						boost: 1.0,
						format: 'yyyy-MM-dd',
						relation: \Spameri\ElasticQuery\Query\Range\Relation::INTERSECTS,
						timeZone: 'UTC',
					),
				),
			),
		);

		$result = $this->search($query);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Range())->run();

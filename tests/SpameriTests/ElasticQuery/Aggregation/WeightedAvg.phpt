<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class WeightedAvg extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_weighted_avg';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'grade' => ['type' => 'long'],
					'weight' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$weightedAvg = new \Spameri\ElasticQuery\Aggregation\WeightedAvg(
			value: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'grade'),
			weight: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'weight'),
		);

		$array = $weightedAvg->toArray();

		\Tester\Assert::same('grade', $array['weighted_avg']['value']['field']);
		\Tester\Assert::same('weight', $array['weighted_avg']['weight']['field']);
	}


	public function testToArrayWithMissing(): void
	{
		$weightedAvg = new \Spameri\ElasticQuery\Aggregation\WeightedAvg(
			value: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'grade', missing: 0),
			weight: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'weight', missing: 1),
			format: '00.00',
		);

		$array = $weightedAvg->toArray();

		\Tester\Assert::same(0, $array['weighted_avg']['value']['missing']);
		\Tester\Assert::same(1, $array['weighted_avg']['weight']['missing']);
		\Tester\Assert::same('00.00', $array['weighted_avg']['format']);
	}


	public function testValueRequiresFieldOrScript(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue();
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testKey(): void
	{
		$weightedAvg = new \Spameri\ElasticQuery\Aggregation\WeightedAvg(
			value: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'grade'),
			weight: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'weight'),
		);

		\Tester\Assert::same('weighted_avg', $weightedAvg->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['grade' => 80, 'weight' => 2]);
		$this->indexDocument(['grade' => 90, 'weight' => 3]);

		$weightedAvg = new \Spameri\ElasticQuery\Aggregation\WeightedAvg(
			value: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'grade'),
			weight: new \Spameri\ElasticQuery\Aggregation\WeightedAvg\WeightedAvgValue(field: 'weight'),
		);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'weighted_grade',
				null,
				$weightedAvg,
			),
		);

		$result = $this->search($elasticQuery);

		\Tester\Assert::same(2, $result->stats()->total());
	}

}

(new WeightedAvg())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Rate extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_rate';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'ts' => ['type' => 'date'],
					'amount' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\Rate(
			unit: 'month',
			field: 'amount',
		);

		$array = $agg->toArray();

		\Tester\Assert::same('month', $array['rate']['unit']);
		\Tester\Assert::same('amount', $array['rate']['field']);
	}


	public function testRequiresUnitOrScript(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\Rate();
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['ts' => '2024-01-01', 'amount' => 10]);
		$this->indexDocument(['ts' => '2024-02-01', 'amount' => 20]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		// rate aggregation must live inside a date_histogram
		$rateLeaf = new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'monthly_rate',
			null,
			new \Spameri\ElasticQuery\Aggregation\Rate(unit: 'month', field: 'amount'),
		);
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'by_month',
			null,
			new \Spameri\ElasticQuery\Aggregation\DateHistogram('ts', calendarInterval: 'month'),
			$rateLeaf,
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Rate())->run();

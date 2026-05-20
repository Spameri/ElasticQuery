<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class TopMetrics extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_top_metrics';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'price' => ['type' => 'long'],
					'ts' => ['type' => 'date'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\TopMetrics(
			metrics: ['price'],
			sort: [['ts' => ['order' => 'desc']]],
			size: 1,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('price', $array['top_metrics']['metrics'][0]['field']);
		\Tester\Assert::same(1, $array['top_metrics']['size']);
	}


	public function testRequiresMetrics(): void
	{
		\Tester\Assert::exception(
			static function (): void {
				new \Spameri\ElasticQuery\Aggregation\TopMetrics([]);
			},
			\Spameri\ElasticQuery\Exception\InvalidArgumentException::class,
		);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100, 'ts' => '2024-01-01']);
		$this->indexDocument(['price' => 200, 'ts' => '2024-06-01']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'latest_price', null, new \Spameri\ElasticQuery\Aggregation\TopMetrics(
				['price'],
				[['ts' => ['order' => 'desc']]],
				1,
			),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new TopMetrics())->run();

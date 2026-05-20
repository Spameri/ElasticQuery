<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class AutoDateHistogram extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_auto_date_histogram';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['ts' => ['type' => 'date']]]];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\AutoDateHistogram(
			field: 'ts',
			buckets: 10,
			format: 'yyyy-MM-dd',
			minimumInterval: 'day',
		);

		$array = $agg->toArray();

		\Tester\Assert::same('ts', $array['auto_date_histogram']['field']);
		\Tester\Assert::same(10, $array['auto_date_histogram']['buckets']);
		\Tester\Assert::same('day', $array['auto_date_histogram']['minimum_interval']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['ts' => '2024-01-01']);
		$this->indexDocument(['ts' => '2024-06-01']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'by_day', null, new \Spameri\ElasticQuery\Aggregation\AutoDateHistogram('ts', buckets: 5),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new AutoDateHistogram())->run();

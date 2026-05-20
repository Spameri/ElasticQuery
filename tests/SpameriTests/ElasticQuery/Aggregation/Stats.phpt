<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Stats extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_stats';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		\Tester\Assert::same('price', (new \Spameri\ElasticQuery\Aggregation\Stats('price'))->toArray()['stats']['field']);
	}


	public function testToArrayWithOptions(): void
	{
		$stats = new \Spameri\ElasticQuery\Aggregation\Stats(
			field: 'price',
			missing: 0,
			format: '00.00',
		);
		$array = $stats->toArray();
		\Tester\Assert::same(0, $array['stats']['missing']);
		\Tester\Assert::same('00.00', $array['stats']['format']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'price_stats', null, new \Spameri\ElasticQuery\Aggregation\Stats('price'),
		));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Stats())->run();

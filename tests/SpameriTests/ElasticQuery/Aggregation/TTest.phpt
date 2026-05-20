<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class TTest extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_t_test';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'pre' => ['type' => 'long'],
					'post' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$agg = new \Spameri\ElasticQuery\Aggregation\TTest(
			a: ['field' => 'pre'],
			b: ['field' => 'post'],
			type: \Spameri\ElasticQuery\Aggregation\TTest::TYPE_PAIRED,
		);

		$array = $agg->toArray();

		\Tester\Assert::same('paired', $array['t_test']['type']);
		\Tester\Assert::same('pre', $array['t_test']['a']['field']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['pre' => 10, 'post' => 12]);
		$this->indexDocument(['pre' => 20, 'post' => 25]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'change', null, new \Spameri\ElasticQuery\Aggregation\TTest(
				['field' => 'pre'],
				['field' => 'post'],
				\Spameri\ElasticQuery\Aggregation\TTest::TYPE_PAIRED,
			),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new TTest())->run();

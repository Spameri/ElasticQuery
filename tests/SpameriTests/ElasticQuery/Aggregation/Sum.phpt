<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Sum extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_sum';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		\Tester\Assert::same('price', (new \Spameri\ElasticQuery\Aggregation\Sum('price'))->toArray()['sum']['field']);
	}


	public function testToArrayWithOptions(): void
	{
		$sum = new \Spameri\ElasticQuery\Aggregation\Sum(
			field: 'price',
			missing: 0,
			format: '0.00',
		);
		$array = $sum->toArray();
		\Tester\Assert::same(0, $array['sum']['missing']);
		\Tester\Assert::same('0.00', $array['sum']['format']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'price_sum', null, new \Spameri\ElasticQuery\Aggregation\Sum('price'),
		));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Sum())->run();

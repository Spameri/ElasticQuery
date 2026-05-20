<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Composite extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_composite';


	protected function mapping(): array|null
	{
		return [
			'mappings' => [
				'properties' => [
					'product' => ['type' => 'keyword'],
					'price' => ['type' => 'long'],
				],
			],
		];
	}


	public function testToArray(): void
	{
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'my_composite',
			source: new \Spameri\ElasticQuery\Aggregation\Composite\TermsSource(
				name: 'product',
				field: 'product',
			),
		);

		$array = $composite->toArray();

		\Tester\Assert::same(
			'product',
			$array['composite']['sources'][0]['product']['terms']['field'],
		);
	}


	public function testToArrayWithMultipleSourcesAndAfter(): void
	{
		$composite = new \Spameri\ElasticQuery\Aggregation\Composite(
			key: 'mc',
			source: new \Spameri\ElasticQuery\Aggregation\Composite\TermsSource(
				name: 'product',
				field: 'product',
				order: 'asc',
				missingBucket: true,
			),
			size: 10,
			after: ['product' => 'a'],
		);
		$composite->addSource(new \Spameri\ElasticQuery\Aggregation\Composite\HistogramSource(
			name: 'price',
			field: 'price',
			interval: 10,
		));

		$array = $composite->toArray();

		\Tester\Assert::count(2, $array['composite']['sources']);
		\Tester\Assert::same(10, $array['composite']['size']);
		\Tester\Assert::same(['product' => 'a'], $array['composite']['after']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['product' => 'a', 'price' => 10]);
		$this->indexDocument(['product' => 'b', 'price' => 20]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'composite_agg',
			null,
			new \Spameri\ElasticQuery\Aggregation\Composite(
				key: 'composite_agg',
				source: new \Spameri\ElasticQuery\Aggregation\Composite\TermsSource(
					name: 'product',
					field: 'product',
				),
			),
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Composite())->run();

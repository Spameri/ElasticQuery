<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Min extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_min';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		$min = new \Spameri\ElasticQuery\Aggregation\Min('price');

		\Tester\Assert::same('price', $min->toArray()['min']['field']);
	}


	public function testToArrayWithOptions(): void
	{
		$min = new \Spameri\ElasticQuery\Aggregation\Min(
			field: 'price',
			missing: 0,
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value * 2", lang: 'painless'),
			format: '00.00',
		);

		$array = $min->toArray();

		\Tester\Assert::same(0, $array['min']['missing']);
		\Tester\Assert::same("doc['price'].value * 2", $array['min']['script']['source']);
		\Tester\Assert::same('00.00', $array['min']['format']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);
		$this->indexDocument(['price' => 200]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price_min',
				null,
				new \Spameri\ElasticQuery\Aggregation\Min('price'),
			),
		);

		$result = $this->search($elasticQuery);

		\Tester\Assert::same(2, $result->stats()->total());
	}


	public function testCreateWithOptions(): void
	{
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'price_min',
				null,
				new \Spameri\ElasticQuery\Aggregation\Min(
					field: 'price',
					missing: 0,
					format: '00.00',
				),
			),
		);

		$result = $this->search($elasticQuery);

		\Tester\Assert::same(1, $result->stats()->total());
	}

}

(new Min())->run();

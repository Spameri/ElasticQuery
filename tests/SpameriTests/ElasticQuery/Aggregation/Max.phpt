<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Max extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_max';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['price' => ['type' => 'long']]]];
	}


	public function testToArray(): void
	{
		\Tester\Assert::same('price', (new \Spameri\ElasticQuery\Aggregation\Max('price'))->toArray()['max']['field']);
	}


	public function testToArrayWithOptions(): void
	{
		$max = new \Spameri\ElasticQuery\Aggregation\Max(
			field: 'price',
			missing: 0,
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].value"),
			format: '00.00',
		);
		$array = $max->toArray();
		\Tester\Assert::same(0, $array['max']['missing']);
		\Tester\Assert::same("doc['price'].value", $array['max']['script']['source']);
		\Tester\Assert::same('00.00', $array['max']['format']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'price_max', null, new \Spameri\ElasticQuery\Aggregation\Max('price'),
		));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new Max())->run();

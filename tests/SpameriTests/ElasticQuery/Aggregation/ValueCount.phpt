<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class ValueCount extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_value_count';


	public function testToArray(): void
	{
		\Tester\Assert::same('price', (new \Spameri\ElasticQuery\Aggregation\ValueCount('price'))->toArray()['value_count']['field']);
	}


	public function testToArrayWithScript(): void
	{
		$vc = new \Spameri\ElasticQuery\Aggregation\ValueCount(
			field: 'price',
			script: new \Spameri\ElasticQuery\Script(source: "doc['price'].size()"),
		);
		\Tester\Assert::same("doc['price'].size()", $vc->toArray()['value_count']['script']['source']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['price' => 100]);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'price_count', null, new \Spameri\ElasticQuery\Aggregation\ValueCount('price'),
		));

		\Tester\Assert::same(1, $this->search($elasticQuery)->stats()->total());
	}

}

(new ValueCount())->run();

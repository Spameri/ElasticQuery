<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Filter extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_filter';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['status' => ['type' => 'keyword']]]];
	}


	public function testToArrayEmpty(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter();

		$array = $filter->toArray();

		\Tester\Assert::true(isset($array['filter']));
	}


	public function testToArrayWithQuery(): void
	{
		$filter = new \Spameri\ElasticQuery\Aggregation\Filter(
			filter: new \Spameri\ElasticQuery\Query\Term('status', 'active'),
		);

		$array = $filter->toArray();

		\Tester\Assert::same('active', $array['filter']['term']['status']['value']);
	}


	public function testKey(): void
	{
		\Tester\Assert::same('filter', (new \Spameri\ElasticQuery\Aggregation\Filter())->key());
	}


	public function testCreate(): void
	{
		$this->indexDocument(['status' => 'active']);
		$this->indexDocument(['status' => 'inactive']);

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(
			new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
				'active_only',
				null,
				new \Spameri\ElasticQuery\Aggregation\Filter(
					filter: new \Spameri\ElasticQuery\Query\Term('status', 'active'),
				),
			),
		);

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Filter())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Aggregation;

require_once __DIR__ . '/../../bootstrap.php';


class Filters extends \SpameriTests\ElasticQuery\AbstractElasticTestCase
{

	protected const INDEX = 'spameri_test_aggregation_filters';


	protected function mapping(): array|null
	{
		return ['mappings' => ['properties' => ['status' => ['type' => 'keyword']]]];
	}


	public function testToArray(): void
	{
		$filters = new \Spameri\ElasticQuery\Aggregation\Filters();
		$filters->addFilter('a', new \Spameri\ElasticQuery\Query\Term('status', 'a'));
		$filters->addFilter('b', new \Spameri\ElasticQuery\Query\Term('status', 'b'));

		$array = $filters->toArray();

		\Tester\Assert::same('a', $array['filters']['filters']['a']['term']['status']['value']);
		\Tester\Assert::same('b', $array['filters']['filters']['b']['term']['status']['value']);
	}


	public function testOtherBucket(): void
	{
		$filters = new \Spameri\ElasticQuery\Aggregation\Filters(
			otherBucket: true,
			otherBucketKey: 'rest',
		);
		$filters->addFilter('a', new \Spameri\ElasticQuery\Query\Term('status', 'a'));

		$array = $filters->toArray();

		\Tester\Assert::true($array['filters']['other_bucket']);
		\Tester\Assert::same('rest', $array['filters']['other_bucket_key']);
	}


	public function testCreate(): void
	{
		$this->indexDocument(['status' => 'active']);
		$this->indexDocument(['status' => 'inactive']);

		$filters = new \Spameri\ElasticQuery\Aggregation\Filters();
		$filters->addFilter('active', new \Spameri\ElasticQuery\Query\Term('status', 'active'));
		$filters->addFilter('inactive', new \Spameri\ElasticQuery\Query\Term('status', 'inactive'));

		$elasticQuery = new \Spameri\ElasticQuery\ElasticQuery();
		$elasticQuery->aggregation()->add(new \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection(
			'by_status', null, $filters,
		));

		\Tester\Assert::same(2, $this->search($elasticQuery)->stats()->total());
	}

}

(new Filters())->run();

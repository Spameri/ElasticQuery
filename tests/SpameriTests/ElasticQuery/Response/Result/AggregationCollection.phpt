<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class AggregationCollection extends \Tester\TestCase
{

	public function testCreateEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection();

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(0, $items);
	}


	public function testCreateWithAggregations(): void
	{
		$agg1 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);
		$agg2 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'brands',
			1,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection($agg1, $agg2);

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(2, $items);
	}


	public function testGetAggregation(): void
	{
		$agg1 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);
		$agg2 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'brands',
			1,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection($agg1, $agg2);

		\Tester\Assert::same($agg1, $collection->getAggregation('categories'));
		\Tester\Assert::same($agg2, $collection->getAggregation('brands'));
	}


	public function testGetAggregationNotFound(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection();

		\Tester\Assert::null($collection->getAggregation('nonexistent'));
	}


	public function testIterate(): void
	{
		$agg1 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'first',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);
		$agg2 = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'second',
			1,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$collection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection($agg1, $agg2);

		$names = [];
		foreach ($collection as $aggregation) {
			$names[] = $aggregation->name();
		}

		\Tester\Assert::same(['first', 'second'], $names);
	}

}

(new AggregationCollection())->run();

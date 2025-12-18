<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result;

require_once __DIR__ . '/../../../bootstrap.php';


class Aggregation extends \Tester\TestCase
{

	public function testCreate(): void
	{
		$bucketCollection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection();
		$aggregationCollection = new \Spameri\ElasticQuery\Response\Result\AggregationCollection();
		$hitCollection = new \Spameri\ElasticQuery\Response\Result\HitCollection();

		$aggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			$bucketCollection,
			$aggregationCollection,
			$hitCollection,
		);

		\Tester\Assert::same('categories', $aggregation->name());
		\Tester\Assert::same(0, $aggregation->position());
		\Tester\Assert::same($bucketCollection, $aggregation->buckets());
		\Tester\Assert::same($aggregationCollection, $aggregation->aggregations());
		\Tester\Assert::same($hitCollection, $aggregation->hits());
	}


	public function testCountBuckets(): void
	{
		$bucket1 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('electronics', 100, 0);
		$bucket2 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('books', 50, 1);
		$bucket3 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('clothing', 30, 2);

		$bucketCollection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(
			$bucket1,
			$bucket2,
			$bucket3,
		);

		$aggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			$bucketCollection,
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		\Tester\Assert::same(180, $aggregation->countBuckets());
	}


	public function testCountBucketsEmpty(): void
	{
		$aggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'empty_agg',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		\Tester\Assert::same(0, $aggregation->countBuckets());
	}


	public function testWithNestedAggregations(): void
	{
		$nestedAggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'sub_category',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection(),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$aggregation = new \Spameri\ElasticQuery\Response\Result\Aggregation(
			'categories',
			0,
			new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(),
			new \Spameri\ElasticQuery\Response\Result\AggregationCollection($nestedAggregation),
			new \Spameri\ElasticQuery\Response\Result\HitCollection(),
		);

		$foundNested = $aggregation->aggregations()->getAggregation('sub_category');

		\Tester\Assert::same($nestedAggregation, $foundNested);
	}

}

(new Aggregation())->run();

<?php declare(strict_types = 1);

namespace SpameriTests\ElasticQuery\Response\Result\Aggregation;

require_once __DIR__ . '/../../../../bootstrap.php';


class BucketCollection extends \Tester\TestCase
{

	public function testCreateEmpty(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection();

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(0, $items);
	}


	public function testCreateWithBuckets(): void
	{
		$bucket1 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('electronics', 100, 0);
		$bucket2 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('books', 50, 1);

		$collection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection($bucket1, $bucket2);

		$items = \iterator_to_array($collection);
		\Tester\Assert::count(2, $items);
	}


	public function testIterate(): void
	{
		$bucket1 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('first', 10, 0);
		$bucket2 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('second', 20, 1);
		$bucket3 = new \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket('third', 30, 2);

		$collection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection(
			$bucket1,
			$bucket2,
			$bucket3,
		);

		$keys = [];
		$docCounts = [];
		foreach ($collection as $bucket) {
			$keys[] = $bucket->key();
			$docCounts[] = $bucket->docCount();
		}

		\Tester\Assert::same(['first', 'second', 'third'], $keys);
		\Tester\Assert::same([10, 20, 30], $docCounts);
	}


	public function testGetIterator(): void
	{
		$collection = new \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection();

		\Tester\Assert::type(\ArrayIterator::class, $collection->getIterator());
	}

}

(new BucketCollection())->run();

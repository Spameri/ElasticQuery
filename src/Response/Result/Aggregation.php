<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Aggregation
{

	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var int
	 */
	private $position;
	/**
	 * @var \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection
	 */
	private $bucketCollection;
	/**
	 * @var \Spameri\ElasticQuery\Response\Result\AggregationCollection
	 */
	private $aggregations;


	public function __construct(
		string $name
		, int $position
		, \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection $bucketCollection
		, \Spameri\ElasticQuery\Response\Result\AggregationCollection $subAggregations,
	)
	{
		$this->name = $name;
		$this->position = $position;
		$this->bucketCollection = $bucketCollection;
		$this->aggregations = $subAggregations;
	}


	public function name(): string
	{
		return $this->name;
	}


	public function position(): int
	{
		return $this->position;
	}


	public function buckets(): \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection
	{
		return $this->bucketCollection;
	}


	public function countBuckets(): int
	{
		$count = 0;
		/** @var \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket $bucket */
		foreach ($this->bucketCollection as $bucket) {
			$count += $bucket->docCount();
		}

		return $count;
	}


	public function aggregations(): \Spameri\ElasticQuery\Response\Result\AggregationCollection
	{
		return $this->aggregations;
	}

}

<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class Aggregation
{

	public function __construct(
		private string $name,
		private int $position,
		private \Spameri\ElasticQuery\Response\Result\Aggregation\BucketCollection $bucketCollection,
		private \Spameri\ElasticQuery\Response\Result\AggregationCollection $aggregations,
		private \Spameri\ElasticQuery\Response\Result\HitCollection $hits,
	)
	{
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


	public function hits(): \Spameri\ElasticQuery\Response\Result\HitCollection
	{
		return $this->hits;
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

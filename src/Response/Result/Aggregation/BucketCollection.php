<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result\Aggregation;


class BucketCollection implements \IteratorAggregate
{

	/**
	 * @var \Spameri\ElasticQuery\Response\Result\Aggregation\Bucket[]
	 */
	private $buckets;


	public function __construct(
		\Spameri\ElasticQuery\Response\Result\Aggregation\Bucket ... $buckets
	)
	{
		$this->buckets = $buckets;
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->buckets);
	}

}

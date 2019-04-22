<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Response\Result;


class AggregationCollection implements \IteratorAggregate
{

	/**
	 * @var array<\Spameri\ElasticQuery\Response\Result\Aggregation>
	 */
	private $aggregations;


	public function __construct(
		\Spameri\ElasticQuery\Response\Result\Aggregation ... $aggregations
	)
	{
		$this->aggregations = $aggregations;
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->aggregations);
	}

}

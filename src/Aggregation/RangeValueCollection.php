<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class RangeValueCollection implements \IteratorAggregate
{

	/**
	 * @var array<\Spameri\ElasticQuery\Aggregation\RangeValue>
	 */
	private array $collection;


	public function __construct(
		RangeValue ... $collection,
	)
	{
		$this->collection = $collection;
	}


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->collection);
	}


	public function add(
		\Spameri\ElasticQuery\Aggregation\RangeValue $rangeValue,
	): void
	{
		$this->collection[] = $rangeValue;
	}

}

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


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->aggregations);
	}


	public function getAggregation(
		string $name
	): ?\Spameri\ElasticQuery\Response\Result\Aggregation
	{
		foreach ($this->aggregations as $aggregation) {
			if ($aggregation->name() === $name) {
				return $aggregation;
			}
		}

		return NULL;
	}

}

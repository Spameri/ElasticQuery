<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class LeafAggregationCollection implements LeafAggregationInterface, \IteratorAggregate
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var \Spameri\ElasticQuery\Filter\FilterCollection
	 */
	private $filter;

	/**
	 * @var array<\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface>
	 */
	private $aggregations;


	public function __construct(
		string $name
		, ?\Spameri\ElasticQuery\Filter\FilterCollection $filter
		, \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface ... $aggregations
	)
	{
		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}

		$this->name = $name;
		$this->filter = $filter;
		$this->aggregations = $aggregations;
	}


	public function key() : string
	{
		return $this->name;
	}


	public function filter() : \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->aggregations);
	}


	public function toArray() : array
	{
		$array = [];

		foreach ($this->aggregations as $aggregation) {
			if ($aggregation instanceof \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection) {
				$array[$this->key()]['aggregations'][$aggregation->key()] = $aggregation->toArray()[$aggregation->key()];

			} else {
				$array[$this->key()]['aggregations'][$aggregation->key()] = $aggregation->toArray();
			}
		}

		if ($this->filter) {
			$array[$this->key()]['filter'] = $this->filter->toArray();
		}

		return $array;
	}

}

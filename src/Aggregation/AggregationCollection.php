<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class AggregationCollection implements LeafAggregationInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Filter\FilterCollection
	 */
	private $filter;

	/**
	 * @var array<\Spameri\ElasticQuery\Aggregation\LeafAggregationCollection>
	 */
	private $aggregations;


	public function __construct(
		?\Spameri\ElasticQuery\Filter\FilterCollection $filter = NULL
		, \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection ... $aggregations
	)
	{
		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}

		$this->filter = $filter;
		$this->aggregations = $aggregations;
	}


	public function key() : string
	{
		return 'top-aggs-collection';
	}


	public function filter() : \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function add(
		LeafAggregationCollection $leafAggregation
	) : void
	{
		$this->aggregations[$leafAggregation->key()] = $leafAggregation;
	}


	public function keys() : array
	{
		return \array_map('\strval', \array_keys($this->aggregations));
	}


	public function isKey(
		string $key
	) : bool
	{
		return \array_key_exists($key, \array_map('\strval', \array_keys($this->aggregations)));
	}


	public function count() : int
	{
		return \count($this->aggregations);
	}


	public function toArray() : array
	{
		$array = [];

		foreach ($this->aggregations as $aggregation) {
			$array[$aggregation->key()] = $aggregation->toArray()[$aggregation->key()];
		}

		return $array;
	}

}

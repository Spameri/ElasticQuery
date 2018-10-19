<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class AggregationCollection implements LeafAggregationInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Filter\FilterCollection
	 */
	private $filter;

	/**
	 * @var \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection[]
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
		return '';
	}


	public function filter() : \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function toArray() : array
	{
		$array = [];

		foreach ($this->aggregations as $aggregation) {
			$array = $array + $aggregation->toArray();
		}

		return $array;
	}

}

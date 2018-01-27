<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class AggregationCollection extends AbstractLeafAggregation
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
	 * @var \Spameri\ElasticQuery\Aggregation\AbstractLeafAggregation
	 */
	private $aggregations;


	public function __construct(
		string $name,
		?\Spameri\ElasticQuery\Filter\FilterCollection $filter,
		\Spameri\ElasticQuery\Aggregation\AbstractLeafAggregation ... $aggregations
	)
	{
		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}

		$this->name = $name;
		$this->filter = $filter;
		$this->aggregations = $aggregations;
	}


	public function name() : string
	{
		return $this->name;
	}


	public function key() : string
	{
		return $this->name;
	}


	public function toArray() : array
	{
		$array['aggregations'][$this->name] = [];

		if ($this->filter) {
			$array['aggregations'][$this->name] = $this->filter->toArray();
		}

		foreach ($this->aggregations as $aggregation) {
			$array['aggregations'][$this->name]['aggregations'][$aggregation->name()] = $aggregation->toArray();
		}

		return $array;
	}

}

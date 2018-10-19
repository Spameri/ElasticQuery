<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class LeafAggregationCollection implements LeafAggregationInterface
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
	 * @var \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
	 */
	private $aggregations;


	public function __construct(
		string $name,
		?\Spameri\ElasticQuery\Filter\FilterCollection $filter,
		\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface ... $aggregations
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


	public function toArray() : array
	{
		$array = [];

		foreach ($this->aggregations as $aggregation) {
			if ($aggregation instanceof LeafAggregationCollection) {
				$array[$this->key()]['aggs'] = $aggregation->toArray();

			} else {
				$array[$this->key()] = $array + $aggregation->toArray();
			}
		}

		return $array;
	}

}

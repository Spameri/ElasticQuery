<?php declare(strict_types=1);

namespace Spameri\ElasticQuery;


class ElasticQuery implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	/**
	 * @var \Spameri\ElasticQuery\Query\QueryCollection
	 */
	private $query;

	/**
	 * @var \Spameri\ElasticQuery\Filter\FilterCollection
	 */
	private $filter;

	/**
	 * @var \Spameri\ElasticQuery\Options\SortCollection
	 */
	private $sort;

	/**
	 * @var \Spameri\ElasticQuery\Aggregation\AggregationCollection
	 */
	private $aggregation;

	/**
	 * @var ?int
	 */
	private $from;

	/**
	 * @var ?int
	 */
	private $size;


	public function __construct(
		?\Spameri\ElasticQuery\Query\QueryCollection $query = NULL
		, ?\Spameri\ElasticQuery\Filter\FilterCollection $filter = NULL
		, ?\Spameri\ElasticQuery\Options\SortCollection $sort = NULL
		, ?\Spameri\ElasticQuery\Aggregation\AggregationCollection $aggregation = NULL
		, ?int $from = NULL
		, ?int $size = NULL
	)
	{
		if ( ! $query) {
			$query = new \Spameri\ElasticQuery\Query\QueryCollection();
		}
		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}
		if ( ! $sort) {
			$sort = new \Spameri\ElasticQuery\Options\SortCollection();
		}
		if ( ! $aggregation) {
			$aggregation = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();
		}
		$this->query = $query;
		$this->filter = $filter;
		$this->sort = $sort;
		$this->aggregation = $aggregation;
		$this->from = $from;
		$this->size = $size;
	}


	public function query() : \Spameri\ElasticQuery\Query\QueryCollection
	{
		return $this->query;
	}


	public function filter() : \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function toArray() : array
	{
		$array = [];

		$queryArray = $this->query->toArray();
		if ($queryArray) {
			$array['query'] = $queryArray;
		}

		$filterArray = $this->filter->toArray();
		if ($filterArray) {
			$array['filter'] = $filterArray;
		}

		$sortArray = $this->sort->toArray();
		if ($sortArray) {
			$array['sort'] = $sortArray;
		}

		$aggregation = $this->aggregation->toArray();
		if ($aggregation) {
			$array['aggs'] = $aggregation;
		}

		if ($this->size !== NULL) {
			$array['size'] = $this->size;
		}

		if ($this->from !== NULL) {
			$array['from'] = $this->from;
		}

		return $array;
	}

}

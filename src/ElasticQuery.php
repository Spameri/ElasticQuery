<?php declare(strict_types = 1);

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
	 * @var \Spameri\ElasticQuery\Options
	 */
	private $options;


	public function __construct(
		?\Spameri\ElasticQuery\Query\QueryCollection $query = NULL
		, ?\Spameri\ElasticQuery\Filter\FilterCollection $filter = NULL
		, ?\Spameri\ElasticQuery\Options\SortCollection $sort = NULL
		, ?\Spameri\ElasticQuery\Aggregation\AggregationCollection $aggregation = NULL
		, ?Options $options = NULL
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
		if ( ! $options) {
			$options = new Options();
		}
		$this->query = $query;
		$this->filter = $filter;
		$this->sort = $sort;
		$this->aggregation = $aggregation;
		$this->options = $options;
	}


	public function query(): \Spameri\ElasticQuery\Query\QueryCollection
	{
		return $this->query;
	}


	public function filter(): \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function aggregation(): \Spameri\ElasticQuery\Aggregation\AggregationCollection
	{
		return $this->aggregation;
	}


	public function options(): \Spameri\ElasticQuery\Options
	{
		return $this->options;
	}


	public function addMustQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->query->must()->add($leafQuery);
	}


	public function addMustNotQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->query->mustNot()->add($leafQuery);
	}


	public function addShouldQuery(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->query->should()->add($leafQuery);
	}


	public function addFilter(\Spameri\ElasticQuery\Query\LeafQueryInterface $leafQuery): void
	{
		$this->filter->must()->add($leafQuery);
	}


	public function addAggregation(\Spameri\ElasticQuery\Aggregation\LeafAggregationCollection $aggregation): void
	{
		$this->aggregation->add($aggregation);
	}


	public function toArray(): array
	{
		$array = $this->options->toArray();

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

		return $array;
	}

}

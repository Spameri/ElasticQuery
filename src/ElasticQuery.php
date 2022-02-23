<?php declare(strict_types = 1);

namespace Spameri\ElasticQuery;


class ElasticQuery implements \Spameri\ElasticQuery\Entity\ArrayInterface
{

	private \Spameri\ElasticQuery\Query\QueryCollection $query;

	private \Spameri\ElasticQuery\Filter\FilterCollection $filter;

	private \Spameri\ElasticQuery\Options\SortCollection $sort;

	private \Spameri\ElasticQuery\Aggregation\AggregationCollection $aggregation;

	private \Spameri\ElasticQuery\Options $options;

	private ?\Spameri\ElasticQuery\Highlight $highlight;

	private ?\Spameri\ElasticQuery\FunctionScore $functionScore;


	public function __construct(
		?\Spameri\ElasticQuery\Query\QueryCollection $query = NULL,
		?\Spameri\ElasticQuery\Filter\FilterCollection $filter = NULL,
		?\Spameri\ElasticQuery\Options\SortCollection $sort = NULL,
		?\Spameri\ElasticQuery\Aggregation\AggregationCollection $aggregation = NULL,
		?\Spameri\ElasticQuery\Highlight $highlight = NULL,
		?\Spameri\ElasticQuery\FunctionScore $functionScore = NULL,
		?\Spameri\ElasticQuery\Options $options = NULL
	)
	{
		if ($query === NULL) {
			$query = new \Spameri\ElasticQuery\Query\QueryCollection();
		}
		if ($filter === NULL) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}
		if ($sort === NULL) {
			$sort = new \Spameri\ElasticQuery\Options\SortCollection();
		}
		if ($aggregation === NULL) {
			$aggregation = new \Spameri\ElasticQuery\Aggregation\AggregationCollection();
		}
		if ($options === NULL) {
			$options = new \Spameri\ElasticQuery\Options();
		}

		$this->query = $query;
		$this->filter = $filter;
		$this->sort = $sort;
		$this->aggregation = $aggregation;
		$this->options = $options;
		$this->highlight = $highlight;
		$this->functionScore = $functionScore;
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


	public function highlight(): ?\Spameri\ElasticQuery\Highlight
	{
		return $this->highlight;
	}


	public function functionScore(): ?\Spameri\ElasticQuery\FunctionScore
	{
		return $this->functionScore;
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

		if ($this->functionScore !== NULL) {
			$array['query'] = $this->functionScore->toArray($array['query']);
		}

		$filterArray = $this->filter->toArray();
		if ($filterArray) {
			$array['query']['bool']['filter'] = $filterArray;
		}

		$sortArray = $this->sort->toArray();
		if ($sortArray) {
			$array['sort'] = $sortArray;
		}

		$aggregation = $this->aggregation->toArray();
		if ($aggregation) {
			$array['aggs'] = $aggregation;
		}

		if ($this->highlight !== NULL) {
			$array['highlight'] = $this->highlight->toArray();
		}

		return $array;
	}

}

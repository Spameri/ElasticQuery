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


	public function __construct(
		?\Spameri\ElasticQuery\Query\QueryCollection $query
		, ?\Spameri\ElasticQuery\Filter\FilterCollection $filter
		, $aggregation
	)
	{
		if ( ! $query) {
			$query = new \Spameri\ElasticQuery\Query\QueryCollection();
		}

		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}

		$this->query = $query;
		$this->filter = $filter;
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
		$array = [
			'query' 		=> $this->query->toArray(),
			'filter'		=> $this->filter->toArray(),
			'sort',
			'aggregation',
		];

		return $array;
	}
}

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
	 * @var null
	 */
	private $sort;
	/**
	 * @var null
	 */
	private $aggregation;
	/**
	 * @var int
	 */
	private $from;
	/**
	 * @var int
	 */
	private $size;


	public function __construct(
		?\Spameri\ElasticQuery\Query\QueryCollection $query = NULL
		, ?\Spameri\ElasticQuery\Filter\FilterCollection $filter = NULL
		, $sort = NULL
		, $aggregation = NULL
		, int $from = NULL
		, int $size = NULL
	)
	{
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

		if ($this->query) {
			$array['query'] = $this->query->toArray();
		}

		if ($this->filter) {
			$array['filter'] = $this->filter->toArray();
		}

		if ($this->sort) {
			$array['sort'] = $this->sort;
		}

		if ($this->aggregation) {
			$array['aggregation'] = $this->aggregation;
		}

		if ($this->size) {
			$array['size'] = $this->size;
		}

		if ($this->from) {
			$array['from'] = $this->from;
		}

		return $array;
	}
}

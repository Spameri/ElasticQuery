<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-adjacency-matrix-aggregation.html
 */
class AdjacencyMatrix implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @var array<string, \Spameri\ElasticQuery\Query\LeafQueryInterface>
	 */
	private array $filters;


	public function __construct(
		private string $key = 'adjacency_matrix',
		private string|null $separator = null,
	)
	{
		$this->filters = [];
	}


	public function addFilter(
		string $name,
		\Spameri\ElasticQuery\Query\LeafQueryInterface $filter,
	): void
	{
		$this->filters[$name] = $filter;
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$filters = [];
		foreach ($this->filters as $name => $filter) {
			$filters[$name] = $filter->toArray();
		}

		$body = ['filters' => $filters];

		if ($this->separator !== null) {
			$body['separator'] = $this->separator;
		}

		return ['adjacency_matrix' => $body];
	}

}

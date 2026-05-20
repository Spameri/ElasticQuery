<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-adjacency-matrix-aggregation.html
 */
class AdjacencyMatrix implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @var array<string, \Spameri\ElasticQuery\Filter\FilterCollection>
	 */
	private array $filters;


	public function __construct(
		private string $key = 'adjacency_matrix',
	)
	{
		$this->filters = [];
	}


	public function addFilter(
		string $name,
		\Spameri\ElasticQuery\Filter\FilterCollection $filter,
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
			$filterArray = $filter->toArray();
			if ($filterArray === []) {
				$filterArray = ['must' => []];
			}
			$filters[$name] = ['bool' => $filterArray];
		}

		return [
			'adjacency_matrix' => [
				'filters' => $filters,
			],
		];
	}

}

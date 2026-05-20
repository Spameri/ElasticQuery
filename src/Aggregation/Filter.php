<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html
 */
class Filter implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $filter = null,
		private string $key = 'filter',
	)
	{
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
		if ($this->filter === null) {
			return ['filter' => ['bool' => new \stdClass()]];
		}

		return ['filter' => $this->filter->toArray()];
	}

}

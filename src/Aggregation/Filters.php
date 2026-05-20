<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * Generic named-filters bucket aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filters-aggregation.html
 */
class Filters implements LeafAggregationInterface
{

	/**
	 * @var array<string, \Spameri\ElasticQuery\Query\LeafQueryInterface>
	 */
	private array $filters;


	public function __construct(
		private string $key = 'filters',
		private bool|null $otherBucket = null,
		private string|null $otherBucketKey = null,
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

		if ($this->otherBucket !== null) {
			$body['other_bucket'] = $this->otherBucket;
		}

		if ($this->otherBucketKey !== null) {
			$body['other_bucket_key'] = $this->otherBucketKey;
		}

		return ['filters' => $body];
	}

}

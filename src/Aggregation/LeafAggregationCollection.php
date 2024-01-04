<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


class LeafAggregationCollection implements LeafAggregationInterface, \IteratorAggregate
{

	private \Spameri\ElasticQuery\Filter\FilterCollection $filter;

	/**
	 * @var array<\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface>
	 */
	private array $aggregations;


	public function __construct(
		private string $name,
		\Spameri\ElasticQuery\Filter\FilterCollection|null $filter,
		\Spameri\ElasticQuery\Aggregation\LeafAggregationInterface ... $aggregations,
	)
	{
		if ( ! $filter) {
			$filter = new \Spameri\ElasticQuery\Filter\FilterCollection();
		}

		$this->filter = $filter;
		$this->aggregations = $aggregations;
	}


	public function addAggregation(LeafAggregationInterface $aggregation): void
	{
		$this->aggregations[$aggregation->key()] = $aggregation;
	}


	public function key(): string
	{
		return $this->name;
	}


	public function filter(): \Spameri\ElasticQuery\Filter\FilterCollection
	{
		return $this->filter;
	}


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->aggregations);
	}


	public function toArray(): array
	{
		$array = [];
		$hasFilter = \count($this->filter->toArray());

		foreach ($this->aggregations as $aggregation) {
			if ($aggregation instanceof \Spameri\ElasticQuery\Aggregation\LeafAggregationCollection) {
				$array[$this->key()]['aggregations'][$aggregation->key()] = $aggregation->toArray()[$aggregation->key()];

			} elseif ($hasFilter) {
				$array[$this->key()]['aggregations'][$aggregation->key()] = $aggregation->toArray();

			} else {
				$array[$this->key()] = $aggregation->toArray();
			}
		}

		if ($hasFilter) {
			$array[$this->key()]['filter'] = $this->filter->toArray();
		}

		return $array;
	}

}

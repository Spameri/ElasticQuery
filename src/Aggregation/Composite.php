<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-composite-aggregation.html
 */
class Composite implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @var array<string, \Spameri\ElasticQuery\Aggregation\Composite\CompositeSourceInterface>
	 */
	private array $sources;


	/**
	 * @param array<string, mixed>|null $after
	 */
	public function __construct(
		private string $key,
		\Spameri\ElasticQuery\Aggregation\Composite\CompositeSourceInterface $source,
		private int|null $size = null,
		private array|null $after = null,
	)
	{
		$this->sources = [$source->key() => $source];
	}


	public function addSource(
		\Spameri\ElasticQuery\Aggregation\Composite\CompositeSourceInterface $source,
	): void
	{
		$this->sources[$source->key()] = $source;
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
		$sources = [];
		foreach ($this->sources as $name => $source) {
			$sources[] = [$name => $source->toArray()];
		}

		$array = ['sources' => $sources];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->after !== null) {
			$array['after'] = $this->after;
		}

		return ['composite' => $array];
	}

}

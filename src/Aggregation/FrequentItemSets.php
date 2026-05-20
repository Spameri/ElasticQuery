<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-frequent-item-sets-aggregation.html
 */
class FrequentItemSets implements LeafAggregationInterface
{

	/**
	 * @param array<int, array<string, mixed>> $fields Each entry: {field, ?include, ?exclude}
	 */
	public function __construct(
		private array $fields,
		private float|null $minimumSupport = null,
		private int|null $minimumSetSize = null,
		private int|null $size = null,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $filter = null,
		private string $key = 'frequent_item_sets',
	)
	{
		if ($fields === []) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'FrequentItemSets requires at least one field.',
			);
		}
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
		$array = ['fields' => $this->fields];

		if ($this->minimumSupport !== null) {
			$array['minimum_support'] = $this->minimumSupport;
		}

		if ($this->minimumSetSize !== null) {
			$array['minimum_set_size'] = $this->minimumSetSize;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->filter !== null) {
			$array['filter'] = $this->filter->toArray();
		}

		return ['frequent_item_sets' => $array];
	}

}

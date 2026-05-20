<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-bucket-sort-aggregation.html
 */
class BucketSort implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, array<string, mixed>> $sort Sort entries, e.g. [['total_sales' => ['order' => 'desc']]].
	 */
	public function __construct(
		private array $sort = [],
		private int|null $size = null,
		private int|null $from = null,
		private string|null $gapPolicy = null,
		private string $key = 'bucket_sort',
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
		$array = [];

		if ($this->sort !== []) {
			$array['sort'] = $this->sort;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->from !== null) {
			$array['from'] = $this->from;
		}

		if ($this->gapPolicy !== null) {
			$array['gap_policy'] = $this->gapPolicy;
		}

		return [
			'bucket_sort' => $array,
		];
	}

}

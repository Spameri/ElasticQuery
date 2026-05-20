<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geo-line.html
 */
class GeoLine implements LeafAggregationInterface
{

	public function __construct(
		private string $pointField,
		private string $sortField,
		private string $sortOrder = 'asc',
		private bool|null $includeSort = null,
		private int|null $size = null,
		private string $key = 'geo_line',
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
		$array = [
			'point' => ['field' => $this->pointField],
			'sort' => ['field' => $this->sortField],
		];

		if ($this->sortOrder !== 'asc') {
			$array['sort_order'] = $this->sortOrder;
		}

		if ($this->includeSort !== null) {
			$array['include_sort'] = $this->includeSort;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		return ['geo_line' => $array];
	}

}

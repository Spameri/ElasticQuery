<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geotilegrid-aggregation.html
 */
class GeoTileGrid implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<string, array<string, float>>|null $bounds
	 */
	public function __construct(
		private string $field,
		private int|null $precision = null,
		private int|null $size = null,
		private int|null $shardSize = null,
		private array|null $bounds = null,
	)
	{
	}


	public function key(): string
	{
		return 'geotile_grid_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->precision !== null) {
			$array['precision'] = $this->precision;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->bounds !== null) {
			$array['bounds'] = $this->bounds;
		}

		return [
			'geotile_grid' => $array,
		];
	}

}

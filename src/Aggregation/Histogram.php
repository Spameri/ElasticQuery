<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html
 */
class Histogram implements LeafAggregationInterface
{

	/**
	 * @param array<string, string>|null $order
	 */
	public function __construct(
		private string $field,
		private int $interval,
		private int|null $minDocCount = null,
		private \Spameri\ElasticQuery\Aggregation\Histogram\Bounds|null $extendedBounds = null,
		private \Spameri\ElasticQuery\Aggregation\Histogram\Bounds|null $hardBounds = null,
		private float|int|null $offset = null,
		private array|null $order = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private float|int|string|null $missing = null,
		private bool|null $keyed = null,
		private string|null $format = null,
	)
	{
	}


	public function key(): string
	{
		return $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'interval' => $this->interval,
		];

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->extendedBounds !== null) {
			$array['extended_bounds'] = $this->extendedBounds->toArray();
		}

		if ($this->hardBounds !== null) {
			$array['hard_bounds'] = $this->hardBounds->toArray();
		}

		if ($this->offset !== null) {
			$array['offset'] = $this->offset;
		}

		if ($this->order !== null) {
			$array['order'] = $this->order;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->keyed !== null) {
			$array['keyed'] = $this->keyed;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['histogram' => $array];
	}

}

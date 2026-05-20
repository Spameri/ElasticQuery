<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
 */
class Range implements LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private bool $keyed = false,
		private \Spameri\ElasticQuery\Aggregation\RangeValueCollection $ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(),
		private \Spameri\ElasticQuery\Script|null $script = null,
		private float|int|string|null $missing = null,
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
		$array = ['field' => $this->field];

		if ($this->keyed === true) {
			$array['keyed'] = true;
		}

		foreach ($this->ranges as $range) {
			$array['ranges'][] = $range->toArray();
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['range' => $array];
	}


	public function ranges(): \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	{
		return $this->ranges;
	}

}

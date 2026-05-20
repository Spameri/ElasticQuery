<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-daterange-aggregation.html
 */
class DateRange implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private \Spameri\ElasticQuery\Aggregation\RangeValueCollection $ranges = new \Spameri\ElasticQuery\Aggregation\RangeValueCollection(),
		private string|null $format = null,
		private string|null $timeZone = null,
		private bool $keyed = false,
	)
	{
	}


	public function key(): string
	{
		return 'date_range_' . $this->field;
	}


	public function ranges(): \Spameri\ElasticQuery\Aggregation\RangeValueCollection
	{
		return $this->ranges;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		if ($this->timeZone !== null) {
			$array['time_zone'] = $this->timeZone;
		}

		if ($this->keyed === true) {
			$array['keyed'] = true;
		}

		foreach ($this->ranges as $range) {
			$array['ranges'][] = $range->toArray();
		}

		return [
			'date_range' => $array,
		];
	}

}

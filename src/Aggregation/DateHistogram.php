<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-datehistogram-aggregation.html
 */
class DateHistogram implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private string|null $calendarInterval = null,
		private string|null $fixedInterval = null,
		private string|null $format = null,
		private string|null $timeZone = null,
		private int|null $minDocCount = null,
		private string|null $offset = null,
	)
	{
		if ($this->calendarInterval === null && $this->fixedInterval === null) {
			throw new \InvalidArgumentException(
				'Either calendarInterval or fixedInterval must be provided.',
			);
		}

		if ($this->calendarInterval !== null && $this->fixedInterval !== null) {
			throw new \InvalidArgumentException(
				'Only one of calendarInterval or fixedInterval may be provided.',
			);
		}
	}


	public function key(): string
	{
		return 'date_histogram_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->calendarInterval !== null) {
			$array['calendar_interval'] = $this->calendarInterval;
		}

		if ($this->fixedInterval !== null) {
			$array['fixed_interval'] = $this->fixedInterval;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		if ($this->timeZone !== null) {
			$array['time_zone'] = $this->timeZone;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->offset !== null) {
			$array['offset'] = $this->offset;
		}

		return [
			'date_histogram' => $array,
		];
	}

}

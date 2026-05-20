<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-datehistogram-aggregation.html
 */
class DateHistogram implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<string, mixed>|null $extendedBounds
	 * @param array<string, mixed>|null $hardBounds
	 * @param array<string, string>|null $order
	 */
	public function __construct(
		private string $field,
		private string|null $calendarInterval = null,
		private string|null $fixedInterval = null,
		private string|null $format = null,
		private string|null $timeZone = null,
		private int|null $minDocCount = null,
		private string|null $offset = null,
		private array|null $extendedBounds = null,
		private array|null $hardBounds = null,
		private bool|null $keyed = null,
		private array|null $order = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $missing = null,
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
		$array = ['field' => $this->field];

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

		if ($this->extendedBounds !== null) {
			$array['extended_bounds'] = $this->extendedBounds;
		}

		if ($this->hardBounds !== null) {
			$array['hard_bounds'] = $this->hardBounds;
		}

		if ($this->keyed !== null) {
			$array['keyed'] = $this->keyed;
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

		return ['date_histogram' => $array];
	}

}

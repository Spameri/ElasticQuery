<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation\Composite;


class DateHistogramSource implements CompositeSourceInterface
{

	public function __construct(
		private string $name,
		private string $field,
		private string|null $calendarInterval = null,
		private string|null $fixedInterval = null,
		private string|null $format = null,
		private string|null $timeZone = null,
		private string|null $order = null,
		private bool|null $missingBucket = null,
	)
	{
		if ($calendarInterval === null && $fixedInterval === null) {
			throw new \Spameri\ElasticQuery\Exception\InvalidArgumentException(
				'DateHistogramSource requires calendarInterval or fixedInterval.',
			);
		}
	}


	public function key(): string
	{
		return $this->name;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$body = ['field' => $this->field];

		if ($this->calendarInterval !== null) {
			$body['calendar_interval'] = $this->calendarInterval;
		}

		if ($this->fixedInterval !== null) {
			$body['fixed_interval'] = $this->fixedInterval;
		}

		if ($this->format !== null) {
			$body['format'] = $this->format;
		}

		if ($this->timeZone !== null) {
			$body['time_zone'] = $this->timeZone;
		}

		if ($this->order !== null) {
			$body['order'] = $this->order;
		}

		if ($this->missingBucket !== null) {
			$body['missing_bucket'] = $this->missingBucket;
		}

		return ['date_histogram' => $body];
	}

}

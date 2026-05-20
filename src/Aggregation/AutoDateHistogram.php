<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-autodatehistogram-aggregation.html
 */
class AutoDateHistogram implements LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $buckets = null,
		private string|null $format = null,
		private string|null $timeZone = null,
		private string|null $minimumInterval = null,
		private string|null $missing = null,
	)
	{
	}


	public function key(): string
	{
		return 'auto_date_histogram_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->buckets !== null) {
			$array['buckets'] = $this->buckets;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		if ($this->timeZone !== null) {
			$array['time_zone'] = $this->timeZone;
		}

		if ($this->minimumInterval !== null) {
			$array['minimum_interval'] = $this->minimumInterval;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		return ['auto_date_histogram' => $array];
	}

}

<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-time-series-aggregation.html
 */
class TimeSeries implements LeafAggregationInterface
{

	public function __construct(
		private bool|null $keyed = null,
		private int|null $size = null,
		private string $key = 'time_series',
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>|\stdClass>
	 */
	public function toArray(): array
	{
		$array = [];

		if ($this->keyed !== null) {
			$array['keyed'] = $this->keyed;
		}

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		return ['time_series' => $array === [] ? new \stdClass() : $array];
	}

}

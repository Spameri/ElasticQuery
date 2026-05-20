<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-aggregation.html
 */
class Percentiles implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, float|int> $percents
	 */
	public function __construct(
		private string $field,
		private array $percents = [],
		private bool $keyed = true,
	)
	{
	}


	public function key(): string
	{
		return 'percentiles_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->percents !== []) {
			$array['percents'] = $this->percents;
		}

		if ($this->keyed === false) {
			$array['keyed'] = false;
		}

		return [
			'percentiles' => $array,
		];
	}

}

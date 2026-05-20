<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-rank-aggregation.html
 */
class PercentileRanks implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	/**
	 * @param array<int, float|int> $values
	 */
	public function __construct(
		private string $field,
		private array $values,
		private bool $keyed = true,
	)
	{
	}


	public function key(): string
	{
		return 'percentile_ranks_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'values' => $this->values,
		];

		if ($this->keyed === false) {
			$array['keyed'] = false;
		}

		return [
			'percentile_ranks' => $array,
		];
	}

}

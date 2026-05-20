<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-extendedstats-aggregation.html
 */
class ExtendedStats implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private float|null $sigma = null,
	)
	{
	}


	public function key(): string
	{
		return 'extended_stats_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
		];

		if ($this->sigma !== null) {
			$array['sigma'] = $this->sigma;
		}

		return [
			'extended_stats' => $array,
		];
	}

}

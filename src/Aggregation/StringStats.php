<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-string-stats-aggregation.html
 */
class StringStats implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private bool $showDistribution = false,
		private string|null $missing = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
	)
	{
	}


	public function key(): string
	{
		return 'string_stats_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->showDistribution === true) {
			$array['show_distribution'] = true;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		return ['string_stats' => $array];
	}

}

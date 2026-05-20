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
		private float|int|string|null $missing = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $format = null,
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
		$array = ['field' => $this->field];

		if ($this->sigma !== null) {
			$array['sigma'] = $this->sigma;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['extended_stats' => $array];
	}

}

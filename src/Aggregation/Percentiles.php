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
	 * @param array<string, mixed>|null $tdigest
	 * @param array<string, mixed>|null $hdr
	 */
	public function __construct(
		private string $field,
		private array $percents = [],
		private bool $keyed = true,
		private array|null $tdigest = null,
		private array|null $hdr = null,
		private float|int|string|null $missing = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
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
		$array = ['field' => $this->field];

		if ($this->percents !== []) {
			$array['percents'] = $this->percents;
		}

		if ($this->keyed === false) {
			$array['keyed'] = false;
		}

		if ($this->tdigest !== null) {
			$array['tdigest'] = $this->tdigest;
		}

		if ($this->hdr !== null) {
			$array['hdr'] = $this->hdr;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		return ['percentiles' => $array];
	}

}

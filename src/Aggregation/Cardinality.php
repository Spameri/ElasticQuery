<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-cardinality-aggregation.html
 */
class Cardinality implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $precisionThreshold = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private float|int|string|null $missing = null,
		private bool|null $rehash = null,
	)
	{
	}


	public function key(): string
	{
		return 'cardinality_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->precisionThreshold !== null) {
			$array['precision_threshold'] = $this->precisionThreshold;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->rehash !== null) {
			$array['rehash'] = $this->rehash;
		}

		return ['cardinality' => $array];
	}

}

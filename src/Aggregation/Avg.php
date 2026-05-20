<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-avg-aggregation.html
 */
class Avg implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private float|int|string|null $missing = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $format = null,
	)
	{
	}


	public function key(): string
	{
		return 'avg_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['avg' => $array];
	}

}

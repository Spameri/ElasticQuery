<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-boxplot-aggregation.html
 */
class BoxPlot implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int|null $compression = null,
		private float|int|string|null $missing = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $executionHint = null,
	)
	{
	}


	public function key(): string
	{
		return 'boxplot_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->compression !== null) {
			$array['compression'] = $this->compression;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->executionHint !== null) {
			$array['execution_hint'] = $this->executionHint;
		}

		return ['boxplot' => $array];
	}

}

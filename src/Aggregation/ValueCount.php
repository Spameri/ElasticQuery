<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-valuecount-aggregation.html
 */
class ValueCount implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $format = null,
	)
	{
	}


	public function key(): string
	{
		return 'value_count_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['value_count' => $array];
	}

}

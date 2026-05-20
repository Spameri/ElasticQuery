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
		$array = [
			'field' => $this->field,
		];

		if ($this->precisionThreshold !== null) {
			$array['precision_threshold'] = $this->precisionThreshold;
		}

		return [
			'cardinality' => $array,
		];
	}

}

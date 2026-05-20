<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-significantterms-aggregation.html
 */
class SignificantTerms implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public const HEURISTIC_JLH = 'jlh';
	public const HEURISTIC_MUTUAL_INFORMATION = 'mutual_information';
	public const HEURISTIC_CHI_SQUARE = 'chi_square';
	public const HEURISTIC_GND = 'gnd';
	public const HEURISTIC_PERCENTAGE = 'percentage';
	public const HEURISTIC_SCRIPT = 'script_heuristic';

	/**
	 * @param array<string, mixed>|null $heuristic e.g. ['mutual_information' => ['include_negatives' => true]]
	 */
	public function __construct(
		private string $field,
		private int|null $size = null,
		private int|null $minDocCount = null,
		private int|null $shardSize = null,
		private int|null $shardMinDocCount = null,
		private string|null $executionHint = null,
		private \Spameri\ElasticQuery\Query\LeafQueryInterface|null $backgroundFilter = null,
		private array|null $heuristic = null,
	)
	{
	}


	public function key(): string
	{
		return 'significant_terms_' . $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->size !== null) {
			$array['size'] = $this->size;
		}

		if ($this->minDocCount !== null) {
			$array['min_doc_count'] = $this->minDocCount;
		}

		if ($this->shardSize !== null) {
			$array['shard_size'] = $this->shardSize;
		}

		if ($this->shardMinDocCount !== null) {
			$array['shard_min_doc_count'] = $this->shardMinDocCount;
		}

		if ($this->executionHint !== null) {
			$array['execution_hint'] = $this->executionHint;
		}

		if ($this->backgroundFilter !== null) {
			$array['background_filter'] = $this->backgroundFilter->toArray();
		}

		if ($this->heuristic !== null) {
			foreach ($this->heuristic as $name => $config) {
				$array[$name] = $config;
			}
		}

		return ['significant_terms' => $array];
	}

}

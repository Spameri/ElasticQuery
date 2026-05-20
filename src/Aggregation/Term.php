<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;


/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
class Term implements LeafAggregationInterface
{

	private \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection $order;


	/**
	 * @param string|array<int, string|float|int>|null $include
	 * @param string|array<int, string|float|int>|null $exclude
	 */
	public function __construct(
		private string $field,
		private int $size = 0,
		private int|null $missing = null,
		\Spameri\ElasticQuery\Aggregation\Terms\OrderCollection|null $order = null,
		private string|array|null $include = null,
		private string|array|null $exclude = null,
		private string|null $key = null,
		private int|null $minDocCount = null,
		private int|null $shardSize = null,
		private int|null $shardMinDocCount = null,
		private bool|null $showTermDocCountError = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
		private string|null $collectMode = null,
		private string|null $executionHint = null,
		private string|null $valueType = null,
		private string|null $format = null,
	)
	{
		$this->order = $order ?? new \Spameri\ElasticQuery\Aggregation\Terms\OrderCollection();
	}


	public function key(): string
	{
		return $this->key ?? $this->field;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = ['field' => $this->field];

		if ($this->size > 0) {
			$array['size'] = $this->size;
		}

		if ($this->missing !== null) {
			$array['missing'] = $this->missing;
		}

		if (\count($this->order)) {
			$array['order'] = $this->order->toArray();
		}

		if ($this->include !== null) {
			$array['include'] = $this->include;
		}

		if ($this->exclude !== null) {
			$array['exclude'] = $this->exclude;
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

		if ($this->showTermDocCountError !== null) {
			$array['show_term_doc_count_error'] = $this->showTermDocCountError;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		if ($this->collectMode !== null) {
			$array['collect_mode'] = $this->collectMode;
		}

		if ($this->executionHint !== null) {
			$array['execution_hint'] = $this->executionHint;
		}

		if ($this->valueType !== null) {
			$array['value_type'] = $this->valueType;
		}

		if ($this->format !== null) {
			$array['format'] = $this->format;
		}

		return ['terms' => $array];
	}

}

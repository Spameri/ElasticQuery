<?php

declare(strict_types = 1);

namespace Spameri\ElasticQuery\Aggregation;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-diversified-sampler-aggregation.html
 */
class DiversifiedSampler implements \Spameri\ElasticQuery\Aggregation\LeafAggregationInterface
{

	public function __construct(
		private string $field,
		private int $shardSize = 100,
		private int|null $maxDocsPerValue = null,
		private string $key = 'diversified_sampler',
		private string|null $executionHint = null,
		private \Spameri\ElasticQuery\Script|null $script = null,
	)
	{
	}


	public function key(): string
	{
		return $this->key;
	}


	/**
	 * @return array<string, array<string, mixed>>
	 */
	public function toArray(): array
	{
		$array = [
			'field' => $this->field,
			'shard_size' => $this->shardSize,
		];

		if ($this->maxDocsPerValue !== null) {
			$array['max_docs_per_value'] = $this->maxDocsPerValue;
		}

		if ($this->executionHint !== null) {
			$array['execution_hint'] = $this->executionHint;
		}

		if ($this->script !== null) {
			$array['script'] = $this->script->toArray();
		}

		return [
			'diversified_sampler' => $array,
		];
	}

}
